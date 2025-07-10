<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class TadaResource extends JsonResource
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
            'name' => $this->user->firstname.' '.$this->user->lastname,
            'typeName' => $this->tadaType->name ?? '',
            'date' => $this->date !== null ? Carbon::parse($this->date)->format(config('constants.DATE_FORMATE')) : '',
            'location' => $this->to === null && $this->from !== null ? $this->from : '',
            'from' => $this->to !== null && $this->from !== null ? $this->from : '',
            'to' => $this->to !== null  && $this->from !== null ? $this->to : '',
            /* 'start_date' => $this->start_date !== null ? Carbon::parse($this->start_date)->format(config('constants.DATE_FORMATE')) : '',
            'end_date' => $this->end_date !== null ? Carbon::parse($this->end_date)->format(config('constants.DATE_FORMATE')) : '', */
            'expense_name' => $this->expense_name ?? '',
            'amount' => $this->amount > 0 ? config('constants.currency_symbol').' '.$this->amount : '',
            'photo' => $this->photo !== null ? asset('storage/'.$this->photo) : '',
            // 'lat' => $this->lat??'',
            // 'long' => $this->long??'',
            'km' => $this->km > 0 ? ($this->km.' KM') : '',
            'per_km_price' => $this->km > 0 ? (config('constants.currency_symbol').' '.$this->per_km_price) : '',
            'comment' => $this->comment ?? '',
            'is_confirm' => $this->is_confirm === 1 ? 'Yes' : 'No',
        ];
    }
}
