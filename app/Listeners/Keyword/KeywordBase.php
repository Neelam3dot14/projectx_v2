<?php

namespace App\Listeners\Keyword;

use App\Events\KeywordEvents;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class KeywordBase
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            KeywordEvents::KEYWORD_CREATED,
            'App\Listeners\Keyword\CreateKeywordListener@createKeyword'
        );

        $events->listen(
            KeywordEvents::KEYWORD_CRAWLED,
            'App\Listeners\Keyword\ScrapeKeywordListener@scrapeKeyword'
        );

        $events->listen(
            KeywordEvents::KEYWORD_SCRAPED,
            'App\Listeners\Keyword\TraceAdsListener@traceAds'
        );

        /*$events->listen(
            KeywordEvents::KEYWORD_TRACED,
            'App\Listeners\Keyword\KeywordListener@traceAds'
        );*/

        /*$events->listen(
            SourceAPIEvents::PROJECT_CANCELLED,
            'App\Listeners\Internal\Project\SourceAPI\Fulcrum\Project\ProjectCancelled@cancelProject'
        );*/
    }
}
