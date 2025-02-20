<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SelectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->withoutWrapping();
        return [
            'value' => $this->value,
            'title' => $this->title,
        ];
    }

    public static function collectionSelect($resource, $title, $key = 'id'): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        foreach ($resource as $index => $value) {
            $resource[$index]['value'] = $value[$key];
            $resource[$index]['title'] = $value[$title];
        }
        return parent::collection($resource);
    }
}
