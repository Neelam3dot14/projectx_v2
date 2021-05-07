<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Internal\Geotarget;
use App\Http\Resources\GeotargetResource;
use App\Http\Resources\CountryResource;

class GeotargetController extends Controller
{
    public function index()
    {        
        $data = Geotarget::select('id','canonical_name','canonical_country','canonical_states','country_code','google_domain','uule_code')
            ->groupBy('canonical_states')
            ->orderBy('id', 'ASC')->limit(10)->get();
        dd($data);
        return GeotargetResource::collection($data);
    }

    public function geoTargetSearch(Request $request)
    {        
        if($request->get('query'))
        {
            $query = $request->get('query');
            $country_code = $request->get('code');
            $data = Geotarget::select('id', 'canonical_states','country_code', 'canonical_country', 'google_domain','uule_code')
                    ->where('canonical_states', 'LIKE', "%{$query}%")
                    ->whereIn('country_code', $country_code)
                    ->groupBy('canonical_states')
                    ->orderBy('id', 'ASC')->get();                    
            return GeotargetResource::collection($data);
        }
    }

    public function findByCanonicalStates($canonical_states)
    {
        $data = Geotarget::select('id', 'canonical_name', 'canonical_states', 'uule_code')
                    ->whereIn('canonical_states', $canonical_states)
                    ->orderByRaw('RAND()')
                    ->get()
                    ->random(1);
        return $data;        
    }

    public function findByCanonicalCountry($canonical_country)
    {
        $data = Geotarget::select('id', 'canonical_name', 'canonical_states', 'uule_code')
                    ->whereIn('country_code', $canonical_country)
                    ->orderByRaw('RAND()')
                    ->get()
                    ->random(1);
                    //->inRandomOrder()
                    //->limit(1)->get();
        return $data;        
    }

    public function getCountryList()
    {
        $data = Geotarget::select('country_code', 'canonical_country')
                    ->distinct('country_code')
                    ->get();
        return CountryResource::collection($data);   
    }

    public function getGeoTargetByCountryCode(Request $request){
        $country_code = $request->input('code');
        $data = Geotarget::select('id', 'canonical_country','canonical_states','country_code','google_domain','uule_code')
            ->whereIn('country_code', $country_code)
            ->groupBy('canonical_states')
            ->orderBy('id', 'ASC')->get();
        return GeotargetResource::collection($data);
    }

}