<?php

return [

    'node_server' => [
        'url' => env('API_NODE_SERVER_URL', 'http://nx.pankajjha.me/'),
    ],

    'rate_limiter' => (bool) env('ENABLE_RATE_LIMITER', false),

    'crawler' => [
        'timeout' => 100,
        'tries' => 3,
        'header' => true,   //True for passing Header
        'apis' => [
            'scraperapi'  => [
              'active' => (bool) env('CRAWLER_SCRAPERAPI_STATUS', true),
              'instances' => env('CRAWLER_SCRAPERAPI_INSTANCE', 4),
              'key' => '5d6f2111b7e0e1da3c1355d6068619f0',
            ],
            'scrapedog'  => [
                'active' => (bool) env('CRAWLER_SCRAPEDOG_STATUS', false),
                'instances' => env('CRAWLER_SCRAPEDOG_INSTANCE', 8),
                'key' => '603dcc57c4be9d15d4d15f2c',
            ],
            'proxy_crawl'  => [
                'active' => (bool) env('CRAWLER_PROXYCRAWL_STATUS', false),
                'instances' => env('CRAWLER_SCRAPERAPI_INSTANCE', 1),
                'key' => 'eJ_Vd6L8f2BvuSf7iDr3VQ',
            ],
            'webscraping_ai'  => [
                'active' => (bool) env('CRAWLER_WEBSCRAPING_AI_STATUS', false),
                'instances' => env('CRAWLER_WEBSCRAPING_AI_INSTANCE', 1),
                'key' => '6a752228-ccce-4fac-af01-5a14255e597a',//51cab757-22d1-414c-9116-f89bac700de0',
            ],
            'scrapingbee'  => [
                'active' => (bool) env('CRAWLER_SCRAPINGBEE_STATUS', false),
                'instances' => env('CRAWLER_SCRAPINGBEE_AI_INSTANCE', 1),
                'key' => 'N5ON06BPI20HL3TDUESIPOIEZI5K0JT7WYGBLLG2TW43O9OO9MXK14RQUYPP1C8EUB8ZR68GX0PXE7MM',
            ],
            'zenscrape'  => [
                'active' => (bool) env('CRAWLER_ZENSCRAPE_STATUS', false),
                'instances' => env('CRAWLER_ZENSCRAPE_INSTANCE', 1),
                'key' => '3cf0ba80-95fd-11eb-ab2d-a3127c591666',
            ],
        ],
    ],

    'scraper' => [
       'timeout' => 100,
       'tries' => 3,
    ],

    'tracer' => [
       'timeout' => 100,
       'tries' => 3,
       'use_proxy' => (bool) env('USE_PROXY', true),
       'whitelisted_domains' => [
            'ad.atdmt.com',
            'clickserve.dartsearch.net',
            'ad.doubleclick.net',
            'googleadservices.com',
            'r.search.yahoo.com',
            'bing.com',
        ],
    ],
    'test' => 'fork testing',
];
