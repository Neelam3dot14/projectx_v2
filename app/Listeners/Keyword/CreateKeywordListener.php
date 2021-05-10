<?php

namespace App\Listeners\Keyword;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\KeywordEvents;
use App\Jobs\CreateAlertRevision;
use App\Jobs\SerpKeyword;
use App\Repositories\CrawlRepository;
use App\Repositories\SerpRepository;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Bus;

class CreateKeywordListener implements ShouldQueue
{    
    public $keyword;
    
    public function createKeyword(KeywordEvents $keywordEvents)
    {
        $this->keyword = $keywordEvents->keyword;
        
        if($this->keyword->campaign->execution_type == 'Crawl'){
            /*Bus::chain([
                new CreateAlertRevision($this->keyword),
                new CrawlKeyword($this->keyword),
            ])->dispatch();*/
            CreateAlertRevision::dispatch($this->keyword);
        }
        if($this->keyword->Campaign->type == 'SERP'){
            SerpKeyword::dispatch($this->keyword);
        }
        return false;
    }

}
