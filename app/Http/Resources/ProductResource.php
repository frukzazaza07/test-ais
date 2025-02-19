<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nameTh' => $this->p_name_th,
            'nameEn' => $this->p_name_en,
            'serialNumber' => $this->p_serial_number,
            'price' => $this->p_price,
            'category' => new ProductCategoryResource($this->productCategory),
        ];
    }
}
