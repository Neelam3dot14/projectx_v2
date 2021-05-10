<?php


namespace App\Repositories\Crawlers;


interface Crawlable
{
    public function crawl($dataArray, $alertRevision);

    public function buildCrawlerUrl($keyword, $alertRevision);

    public function getUserAgent($alertRevision);
}
