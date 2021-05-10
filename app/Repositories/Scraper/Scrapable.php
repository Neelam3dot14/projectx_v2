<?php

namespace App\Repositories\Scraper;

use App\Models\Internal\AlertRevision;

interface Scrapable
{
    public function scrape(AlertRevision $keywordData);
}
