<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GeotargetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'canonical_country' => $this->canonical_country,
            'canonical_states' => $this->canonical_states,
            'country_code' => $this->country_code,
            'google_domain' => $this->google_domain,
            'uule_code' => $this->uule_code,
        ];
    }
}
