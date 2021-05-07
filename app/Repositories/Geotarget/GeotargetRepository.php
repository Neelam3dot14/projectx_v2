<?php
namespace App\Repositories\Geotarget;

use App\Models\Internal\Geotarget;
use App\Http\Resources\GeotargetResource;

class GeotargetRepository
{
    public function checkYahooDomainByCountryCode($country_code)
    {
        $data = Geotarget::where('country_code', $country_code)
                ->whereNotNull('yahoo_domain')
                ->exists();
        return $data;
    }

    public function getYahooDomainByCountryCode($country_code)
    {
        $data = Geotarget::select('yahoo_domain')->where('country_code', $country_code)
                ->limit(1)
                ->get();
        return $data;
    }

    public function getGoogleDomain($country_code)
    {
        $data = Geotarget::select('google_domain', 'country_code')
                    ->where('country_code', $country_code)
                    ->limit(1)
                    ->get();
        return $data[0]['google_domain'];
    }

    public function getLanguage($country_code)
    {
        $data = Geotarget::select('language', 'country_code')
                    ->where('country_code', $country_code)
                    ->limit(1)
                    ->get();
        return $data[0]['language'];
    }

    public function getLocation($country_code, $canonical_states)
    {
        $data = Geotarget::where('country_code', $country_code)
                    ->where('canonical_states', $canonical_states)
                    ->orderBy('id', 'ASC')
                    ->limit(1)
                    ->get();
        $geotarget = [
            'id' => $data[0]->id,
            'canonical_country' => $data[0]->canonical_country,
            'canonical_states' => $data[0]->canonical_states,
            'country_code' => $data[0]->country_code,
            'google_domain' => $data[0]->google_domain,
            'uule_code' => $data[0]->uule_code,
        ];
        return json_encode($geotarget);
    }
    
}