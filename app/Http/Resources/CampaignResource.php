<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $canonical_states = explode(',',$this->canonical_states);
        $gl_code = explode(",",$this->gl_code);
        $google_domain = explode(",",$this->google_domain);

        for($i=0;$i<count($canonical_states);$i++){
            $geotarget[] = [
                            'canonical_states' => $canonical_states[$i],
                            'country_code' => $gl_code[$i],
                            'google_domain' =>$google_domain[$i]
                            ];
        }
        $country_code = explode(',',$this->country);
        foreach($country_code as $v){
            $code[] = ['country_code' => $v];
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'keywords' => $this->keywords,
            'device' => $this->device,
            'search_engine' => $this->search_engine,
            'execution_type' => $this->execution_type,
            'execution_interval' => $this->execution_interval,
            'geotarget_search' => $geotarget,
            'country' => $code,
            'canonical_states' => isset($canonical_states) ? implode(',', $canonical_states) : 'none',
            'gl_code' => implode(',', array_unique($gl_code)),
            'google_domain' => implode(',', array_unique($google_domain)),
            'created_by' => $this->created_by,
            'status' => $this->status,
            'alert_revisions_count' => $this->alert_revisions_count,
            'ad_competitors_count' => $this->ad_competitors_count,
            'ad_hijacks_count' => $this->ad_hijacks_count,
        ];
    }
}
