<?php


namespace App\Repositories\Tracer;


use Illuminate\Support\Facades\Http;
use App\Repositories\Traits\Luminati;
use Illuminate\Support\Facades\Log;

class NodeTracer implements Traceable
{
    use Luminati;

    public function trace($link, $keywordData)
    {
        $this->keywordData = $keywordData;
        $tracerArray = [
            'link' => $this->formatLink($link),
            'user_agent' => $this->getUserAgent($this->keywordData),
            'resolution' => $this->getResolution($this->keywordData),
            //'device' => ucfirst($this->keywordData->campaignKeyword->device),
        ];
        $this->setTracerProxy($tracerArray);
        $response = $this->executeTraceApi($tracerArray);
        if($response->status() == 200){ 
            $responseBody = $response->body();
            return $this->parseResponseJson($responseBody);
        }
        return $response;
    }

    public function formatLink($link)
    {
        //return 'https://www.google.co.in/aclk?sa=L&ai=DChcSEwiFp9iXvPzrAhXWhNUKHYz1ACUYABAAGgJ3cw&sig=AOD64_3r5Y3DY0X-oN8gpR8W4ECounh1zQ&q&adurl&ved=2ahUKEwjVu9GXvPzrAhURzhoKHSxgDrMQ0Qx6BAgLEAE';
        return $link;
    }

    public function executeTraceApi(Array $tracerArray)
    {
        $api_url = config('api.node_server.url')."keywords/trace/";
        try{
            $response = Http::withHeaders([
                'accept-encoding' => 'gzip, deflate',
                'version' => '1.0',
            ])->withOptions([
                'debug' => false,
                'version' => '1.0',
                'Expect' => ''
            ])->timeout(config('api.tracer.timeout'))->post($api_url, $tracerArray); 
            //Log::debug($response->body(), ['TraceResponse', $response->body()]);
            return $response;

        } catch (\Exception $e) {
            return response($e->getMessage(), 501);
        }
    }

    public function parseResponseJson(&$responseJson)
    {
        if(!$responseJson){
            response($responseJson, 581);
        }
        return response($responseJson, 200);
    }

    public function setTracerProxy(&$tracerArray)
    {
        $availableProxies = $this->getAvailableProxy();
        $currentProxy = $availableProxies[array_rand( $availableProxies,1)];
        if( isset($currentProxy['USE_SSL']) ){
            if($currentProxy['USE_SSL'] == true){
                $tracerArray['link'] = str_ireplace( 'http://', 'https://', $tracerArray['link'] );
            } else{
                $tracerArray['link'] = str_ireplace( 'https://', 'http://', $tracerArray['link'] );
            }

        }
        $tracerArray['USE_PROXY'] = config('api.tracer.use_proxy');
        $tracerArray['PROXY_DATA'] = $currentProxy;
    }

    public function getAvailableProxy()
    {
        $proxies = collect([
            'luminati' => $this->getLuminatiProxy(),
            'netnut' => [
                'PROXY_SCHEME' => 'PROXY_SERVER:PROXY_PORT',
                'PROXY_SERVER' => 'gw.ntnt.io',
                'PROXY_PORT' => 5959,
                'AUTHENTICATION' => 'BASIC_AUTH',
                'PROXY_USERNAME' => 'CollegeDunia-cc-mumbai',
                'PROXY_PASSWORD' => 'Mno2A9',
                'USE_SSL' => true,
                'ENABLED' => false,
            ],
        ]);
        return $proxies->where('ENABLED', '=', true)->toArray();
    }

    public function getLuminatiProxy()
    {
        //$campaignCountry = 'US';
        $campaignCountry = strtoupper($this->keywordData->campaignKeyword->country_code);
        $data = [
            'PROXY_SCHEME' => $this->getLuminatiProxyScheme(),
            'PROXY_SERVER' => $this->getLuminatiProxyServer(),
            'PROXY_PORT' => $this->getLuminatiProxyPort(),
            'AUTHENTICATION' => 'BASIC_AUTH',
            'PROXY_USERNAME' => $this->getLuminatiUsername($campaignCountry),
            'PROXY_PASSWORD' => $this->getLuminatiPassword(),
            'USE_SSL' => true,
            'ENABLED' => true,
        ];
        return $data;
    }

    public function getUserAgent($alertRevision)
    {
        $revisionAgent = json_decode($alertRevision->user_agent);
        $user_agent = stripslashes($revisionAgent->agent);
        return $user_agent;
    }

    public function getResolution($alertRevision)
    {
        $device = $alertRevision->campaignKeyword->device;
        if($device == 'desktop'){
            $resolution =  '1920,1080';
        } elseif($device == 'mobile'){
            $resolution =  '480,320';
        } else{
            $resolution = '800,1280';
        }
        return $resolution;
    }

}
