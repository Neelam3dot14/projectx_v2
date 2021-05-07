<?php

namespace App\Repositories\UserAgent;

//use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Models\UserAgent;
use Cache;

/**
 * Class UserAgentRepository.
 */
class UserAgentRepository
{
    /**
     * @return string
     *  Return the model
     */
    protected $expiration;

    public function __construct()
    {
        $this->expiration = config('cache.expiration');
    }

    public function getDesktopUserAgent()
    {
        $value = Cache::remember("useragents.device.desktop", $this->expiration, function (){
            $allAgent = UserAgent::where('device', 'Desktop')->get();
            $arr = [];
            foreach ($allAgent as $agent){
                $data = [
                    'agent' => $agent->user_agent,
                    'browser' => $agent->browser,
                    'device' => $agent->device,
                ];
                array_push($arr, $data);
            }
            return $arr;
        });
        $data = $value[array_rand($value, 1)];
        return $data;
    }

    public function getMobileUserAgent()
    {
        $value = Cache::remember("useragents.device.mobile", $this->expiration, function (){
            $allAgent = UserAgent::where('device', 'Mobile')->get();
            $arr = [];
            foreach ($allAgent as $agent){
                $data = [
                    'agent' => $agent->user_agent,
                    'browser' => $agent->browser,
                    'device' => $agent->device,
                ];
                array_push($arr, $data);
            }
            return $arr;
        });
        $data = $value[array_rand($value, 1)];
        return $data;
    }

    public function getTabletUserAgent()
    {
        $value = Cache::remember("useragents.device.tablet", $this->expiration, function (){
            $allAgent = UserAgent::where('device', 'Tablet')->get();
            $arr = [];
            foreach ($allAgent as $agent){
                $data = [
                    'agent' => $agent->user_agent,
                    'browser' => $agent->browser,
                    'device' => $agent->device,
                ];
                array_push($arr, $data);
            }
            return $arr;
        });
        $data = $value[array_rand($value, 1)];
        return $data;
    }
}
