<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class FormResource extends Resource
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
            'files' => FormFileResource::collection($this->files),
            'name' => $this->name,
        ];
    }
}
