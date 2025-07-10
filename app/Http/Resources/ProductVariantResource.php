<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'variant_id' => $this->id,
            'mrp' => $this->variant_price->price ?? '',
            'sp' => $this->variant_price->price ?? '',
            'qty' => $this->variant_price->case_quantity ?? '',
            'case' => $this->variant_price->case_quantity ?? '',
            'variant_type' => $this->variantType->name??'',
            'variant_value' => $this->variantValue->name??'',
        ];
    }
}
