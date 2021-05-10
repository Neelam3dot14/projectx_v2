<?php
namespace App\Repositories;

use App\Models\Internal\Keyword\AdTrace;
use App\Repositories\Tracer\TracerFactory;
use Illuminate\Support\Facades\Log;

class TraceRepository
{
    public function trace($adLink, $keyword)
    {
        $tracer = TracerFactory::getTracer();
        try{
            $response = $tracer->trace($adLink, $keyword);
        } catch(\Exception $e) {
            Log::error($e->getMessage(), [
                'Scrape Error',
            ]);
            return false;
        }
        return $response;
    }

    public function saveTracedLinks($ad, $traceResponse, $alertRevision)
    {
        $tracedData = json_decode($traceResponse, true);
        $result = (isset($tracedData['result']))?$tracedData['result']:false;
        if(!empty($result)){
            foreach($result as $trace){
                $dataArray = [
                    'ad_id' => $ad->id,
                    'traced_url' => $trace['URL'],
                    'context' => $trace['request_redirect_type'],
                    'redirect_type' => $trace['status'],
                ];
                $ad_trace = AdTrace::create($dataArray);
            }
        }
        $ad->ad_status = 'TRACED';
        $ad->save();
    }

}
