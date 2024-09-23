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
            'SKU' => $this->SKU,
            'name' => $this->name,
            'description' => $this->description,
            'original_price' => $this->price,
            'price_for_user' => $this->when(isset($this->final_price), $this->final_price, $this->price),
        ];
    }
}
