<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\Geotarget\GeotargetRepository;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $geotargetRepo = new GeotargetRepository();
        $google_domain = $geotargetRepo->getGoogleDomain($this->country_code);
        
        return [
            'value' => [
                'country_code' => $this->country_code,
                'canonical_country' => $this->canonical_country,
                'google_domain' => $google_domain
                ],
            'country_code' => $this->country_code,
            'canonical_country' => $this->canonical_country,
        ];
    }
}
