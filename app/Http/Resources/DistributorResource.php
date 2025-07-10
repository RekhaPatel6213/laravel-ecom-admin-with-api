<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DistributorResource extends JsonResource
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
            'firstname' => $this->firstname,
            'lastname' => $this->lastname ?? '',
            'email' => $this->email ?? '',
            'mobile' => $this->mobile,
            'contact_person' => $this->lastname ?? '',
            'state' => $this->state->name ?? '',
            'city' => $this->city->name ?? '',
            'area' => $this->area->name ?? '',
            'zone' => $this->zone->name ?? '',
            'cst_gst_no' => $this->cst_gst_no ?? '',
            'is_interested' => $this->is_interested,
            'meeting_type' => $this->meetingType ? $this->meetingType->name : '',
            'area_of_operation' => $this->area_of_operation ?? '',
            'current_dealership' => $this->current_dealership ?? '',
        ];
    }
}
