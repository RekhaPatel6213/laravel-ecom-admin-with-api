<?php

namespace App\Http\Resources;

use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
            'name' => $this->name,
            'image' => $this->image !== null ? asset('storage/'.$this->image) : '',
            'category_id' => $this->category_id,
            // "variant" => VariantResource::collection($this->product_variant),
            'variant' => ProductVariantResource::collection(ProductVariant::where('product_id', $this->id)->with(['variantType:id,name', 'variantValue:id,name', 'variant_price' => function ($query) {
                $query->where('zone_id', Auth::user()->zone_id);
            }])->get()),
        ];
    }
}
