<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ViewCampaignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (isset($this->whitelisted_domain)){
            $whitelisted_domains = str_replace(",", PHP_EOL, $this->whitelisted_domain);
        } else {
            $whitelisted_domains = '';
        }

        if (isset($this->blacklisted_domain)){
            $blacklisted_domains = str_replace(",", PHP_EOL, $this->blacklisted_domain);
        } else {
            $blacklisted_domains = '';
        }
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            'keywords' => $this->keywords,
            'device' => $this->device,
            'search_engine' => $this->search_engine,
            'execution_type' => $this->execution_type,
            'execution_interval' => $this->execution_interval,
            'country' => $this->country,
            'canonical_states' => $this->canonical_states,
            'gl_code' => $this->gl_code,
            'google_domain' => $this->google_domain,
            'country_location' => json_decode($this->location),
            'state_location' => ($this->canonical_states != "") ? json_decode($this->location) : null,
            'whitelisted_domains'  =>  $whitelisted_domains,
            'blacklisted_domains' => $blacklisted_domains,
            'created_by' => $this->created_by,
            'status' => $this->status,
            /*'alert_revisions_count' => $this->alert_revisions_count,
            'success_revisions_count' => $this->success_revisions_count,
            'crawl_failed_revisions_count' => $this->crawl_failed_revisions_count,
            'scraping_failed_revisions_count' => $this->scraping_failed_revisions_count,
            'pending_revisions_count' => $this->pending_revisions_count,*/
        ];
    }
}
