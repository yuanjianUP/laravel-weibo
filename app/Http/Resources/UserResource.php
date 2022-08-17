<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    private $showSensitiveFields = false;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if(!$this->showSensitiveFields){
            $this->resource->makeHidden(['phone','email']);
        }
        $data = parent::toArray($request);
        $data['bound_phone'] = $this->resource->phone ? true : false;
        return $data;
    }

    public function showSensitiveFields(){
        $this->showSensitiveFields = true;
        return $this;
    }
}
