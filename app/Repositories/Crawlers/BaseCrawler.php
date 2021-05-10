<?php


namespace App\Repositories\Crawlers;

use App\Repositories\Geotarget\GeotargetRepository;
use App\Models\Internal\CampaignKeyword;

abstract class BaseCrawler implements Crawlable
{
    public function crawl($dataArray, $alertRevision)
    {
        dd('Base Crawl Executed');
    }

    public function buildCrawlerUrl($keyword, $alertRevision)
    {
        $geotargetRepo = new GeotargetRepository();
        $search_engine = $keyword->search_engine;
        $finalUrl = '';
        if($search_engine == 'google'){
            $google_uule = $alertRevision->google_uule;
            $Agent = json_decode($alertRevision->user_agent);
            $source = $Agent->browser;
            $protocol = 'https://';
            $domain = 'www.'.$keyword->google_domain.'/';
            $page = 'search';
            $url = $protocol . $domain . $page;
            $queryData = [
                'q' => $keyword->keyword,
                'uule' => urldecode($google_uule),
                'hl' => $keyword->lang,
                'gl' => $keyword->country_code,
                'source_id' => $source,
                'ie' => 'UTF-8',
            ];
            $queryString = http_build_query($queryData);
            $finalUrl = $url . '?' . $queryString;
        } else if($search_engine == 'bing'){
            $protocol = 'https://';
            $domain = 'www.bing.com'.'/';
            $page = 'search';
            $url = $protocol . $domain . $page;
            $queryData = [
                'q' => $keyword->keyword,
                'form' => 'QBRE',
                'cc' => $keyword->country_code,
            ];
            $queryString = http_build_query($queryData);
            $finalUrl = $url . '?' . $queryString;
        } else if($search_engine == 'yahoo'){
            $result = $geotargetRepo->getYahooDomainByCountryCode(strtoupper($keyword->country_code));
            $domain = $result[0]['yahoo_domain'];
            $page = 'search';
            $url = $domain . '/'. $page;
            $queryData = [
                'vc' => $keyword->country_code,
                'vl' => 'lang_en',
                'p' => $keyword->keyword,
                'fl' => '1',
                'ei' => 'UTF-8'
            ];
            $queryString = http_build_query($queryData);
            $finalUrl = $url . '?' . $queryString;
        }
        return $finalUrl;
    }
    
    public function getUserAgent($alertRevision)
    {
        $revisionAgent = json_decode($alertRevision->user_agent);
        $user_agent = stripslashes($revisionAgent->agent);
        //$user_agent = str_replace("\/"," /", $user_agent);
        return $user_agent;
    }

    abstract public function executeCrawlApi($crawlData);
}
