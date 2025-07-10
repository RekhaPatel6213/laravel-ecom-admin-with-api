<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'cart_id' => $this->id,
            'distributor_id' => $this->distributor_id,
            'product_id' => $this->product_id,
            'variant_id' => $this->variant_id,
            'quantity' => $this->quantity,
            'name' => $this->name,
            'image' => $this->image !== null ? asset('storage/'.$this->image) : '',
            'mrp' => $this->mrp,
            'selling_price' => $this->selling_price,
            // 'gst_per' => $this->gst_per,
            // 'gst_val' => $this->gst_val,
            'cgst_per' => $this->cgst_per,
            'cgst_val' => $this->cgst_val,
            'sgst_per' => $this->sgst_per,
            'sgst_val' => $this->sgst_val,
            'amount' => $this->amount,
            /*'with_out_gst_price' => $this->with_out_gst_price,
            'amount_without_gst' => $this->amount_without_gst,
            'total_gst_val' => $this->total_gst_val,*/
        ];
    }
}
