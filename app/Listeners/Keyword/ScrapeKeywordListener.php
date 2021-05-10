<?php


namespace App\Listeners\Keyword;


use App\Events\KeywordEvents;
use App\Jobs\ScrapeKeyword;
use App\Repositories\ScrapeRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;

class ScrapeKeywordListener implements ShouldQueue
{
    public $keyword, $alertRevision;

    public function scrapeKeyword(KeywordEvents $keywordEvents)
    {
        $this->keyword = $keywordEvents->keyword;
        $this->alertRevision = $keywordEvents->alert_id;
        $this->alertRevision->status = 'SCRAPING';
        $this->alertRevision->save();
        ScrapeKeyword::dispatch($this->keyword, $this->alertRevision);
    }

}
