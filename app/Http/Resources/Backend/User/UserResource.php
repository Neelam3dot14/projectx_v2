<?php

namespace App\Http\Resources\Backend\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => date('d-m-Y h:i:s', strtotime($this->created_at)),
            'active' => $this->active,
            'roles' => $this->roles()->pluck('name')->implode(','),
        ];
    }
}
