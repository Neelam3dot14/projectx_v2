<?php

namespace App\Http\Resources\Keyword;

use Illuminate\Http\Resources\Json\JsonResource;

class KeywordAdTraceResource extends JsonResource
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
            'ad_id' => $this->ad_id,
            'traced_url' => $this->traced_url,
            'context' => $this->context,
            'redirect_type' => $this->redirect_type,
        ];
    }
}
