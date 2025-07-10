<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetingResource extends JsonResource
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
            'id' => $this->id,
            'distributor' => ($this->distributor->firstname ?? ''), //.' '.($this->distributor->lastname ?? ''),
            'shop' => $this->shop->name ?? '',
            'type' => $this->type->name ?? '',
            'meeting_date' => $this->meeting_date->format(config('constants.DATE_FORMATE')),
            'start_time' => Carbon::parse($this->start_time)->format(config('constants.TIME_FORMATE')),
            'start_latitude' => $this->start_latitude,
            'start_longitude' => $this->start_longitude,
            'end_time' => $this->end_time !== null ? Carbon::parse($this->end_time)->format(config('constants.TIME_FORMATE')) : null,
            'end_latitude' => $this->end_latitude ?? '',
            'end_longitude' => $this->end_longitude ?? '',
            'comments' => $this->comments ?? '',
            'purposeOfMeeting' => $this->purpose ?? '',
            'attachment1' => asset('storage/'.$this->attachment1),
            'attachment2' => asset('storage/'.$this->attachment2),
            'attachment3' => asset('storage/'.$this->attachment3),
            // "is_interested" => $this->distributor->is_interested??0,
        ];
    }
}
