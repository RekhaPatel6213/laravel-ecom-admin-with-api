<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
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
            'contact_person_name' => $this->contact_person_name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'address' => $this->address,
            // "full_address" => ($this->address??'').','.($this->city->name??'').','.($this->state->name??'').','.($this->country->name??''),
            'state' => $this->state->name ?? '',
            'city' => $this->city->name ?? '',
            'area' => $this->area->name ?? '',
            'gstin_no' => $this->gstin_no ?? '',
            // "pincode" => $this->pincode
        ];
    }
}
