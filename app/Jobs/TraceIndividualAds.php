<?php

namespace App\Jobs;

use App\Repositories\TraceRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\RateLimitedMiddleware\RateLimited;
use App\Jobs\AdsCompetition;

class TraceIndividualAds implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $alertRevision, $tracer, $metadata, $ad, $status, $elapsed_time;

    /**
     * Delete the job if its models no longer exist.
     *
     * @var bool
     */
    public $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ad, $alertRevision, $metadata = [])
    {
        $this->onQueue('tracer');
        $this->ad = $ad;
        $this->alertRevision = $alertRevision;
        $this->metadata = $metadata;
    }

    /*
    * Determine the time at which the job should timeout.
    *
    */
    public function retryUntil()
    {
        return now()->addHours(2);
    }

    public function middleware()
    {
        $shouldRateLimitJobs = config('api.rate_limiter');
        $rateLimitedMiddleware = (new RateLimited())
            ->enabled($shouldRateLimitJobs)
            ->allow(20)
            ->everyMinute()
            ->releaseAfterSeconds(120);

        return [$rateLimitedMiddleware];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->tracer =  new TraceRepository();

        $startTime =  microtime(true);
        $response = $this->tracer->trace($this->ad->ad_link, $this->alertRevision);
        $this->elapsed_time = number_format(microtime(true) - $startTime,4);
        $this->status = $response->status();
        //parsing Tracer Metadata
        $this->metadata = $this->parseTracerMetadata();

        if ($this->status == 200){
            $this->tracer->saveTracedLinks($this->ad, $response->content(), $this->alertRevision);
            AdsCompetition::dispatch($this->alertRevision, $this->ad);
        } elseif ($this->status == 501) {
            $error_response = $this->parseErrorResponse("INTERNAL_SERVER_ERROR_DETECTED", $response->content());
            $this->saveTraceErrorResponse($error_response);
            $this->retryTracing();
        } elseif ($this->status == 581) {
            $error_response = $this->parseErrorResponse("TRACE_ERROR_DETECTED", $response->content());
            $this->saveTraceErrorResponse($error_response);
        }
        else{
            $error_response = $this->parseErrorResponse($response->getReasonPhrase()."_DETECTED", $response->body());
            $this->saveTraceErrorResponse($error_response);
            $this->retryTracing();
        }
    }

    public function parseTracerMetadata()
    {
        return [
            'TRACER' => 'NODEX TRACER',
            'ElAPSED_TIME' => $this->elapsed_time,
            'API_STATUS' => $this->status,
        ];
    }

    public function parseErrorResponse($error_code, $error_msg)
    {
        return [
            'CODE' => $error_code,
            'MESSAGE' => $error_msg,
            'TRACER' => 'NODEX TRACER',
            'ElAPSED_TIME' => $this->elapsed_time,
            'API_STATUS' => $this->status,
        ];
    }

    public function saveTraceErrorResponse($error_response)
    {
        $this->alertRevision->tracing_error = isset($this->alertRevision->tracing_error) ? $this->alertRevision->tracing_error .",". json_encode($error_response) : json_encode($error_response);
        $this->alertRevision->tracer_metadata = json_encode($this->metadata);
        $this->alertRevision->status = 'TRACING_FAILED';
        $this->alertRevision->save();
        $this->ad->ad_status = 'TRACING_FAILED';
        $this->ad->save();
    }

    public function retryTracing()
    {
        if ($this->checkTries($this->ad->tries)){
            $this->ad->tries = ++$this->ad->tries;
            $this->ad->ad_status = 'TRACING_AGAIN';
            $this->ad->save();
            TraceIndividualAds::dispatch($this->ad, $this->alertRevision)
                ->delay(now()->addMinutes(1));
        } else{
            $this->ad->ad_status = 'TRACING_FAILED';
            $this->ad->save();
            $this->fail();
            $this->delete();
        }
    }

    public function checkTries($tries)
    {
        if($tries < config('api.tracer.tries')){
            return true;
        } else {
            return false;
        }
    }

    public function failed($exception = null)
    {
        $message = isset($exception) ? $exception->getMessage() : 'manually failed jobs';
        $error_response = $this->parseErrorResponse("TRACE_JOB_FAILURE_DETECTED", $message);
        $this->saveTraceErrorResponse($error_response);
        $this->retryTracing();
        $this->fail();
        $this->delete();
    }
}
