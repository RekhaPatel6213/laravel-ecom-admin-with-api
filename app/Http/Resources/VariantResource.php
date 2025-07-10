<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'variant_id' => $this->id,
            'mrp' => $this->mrp,
            'sp' => $this->sp,
            'qty' => $this->qty_stock,
            'variant_type' => $this->variantType->name,
            'variant_value' => $this->variantValue->name,
        ];
    }
}
