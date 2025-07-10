<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_no' => $this->order_no,
            // 'invoice_no' => $this->invoice_no,
            'order_type' => ($this->distributor_id != null && $this->shop_id != null) ? 'Retailer' : 'Primary',
            'distributor_name' => ($this->distributor->firstname ?? '').' '.($this->distributor->lastname ?? ''),
            'shop_name' => $this->shop->name ?? '',
            'meeting' => ! empty($this->meeting) ? getGoogleMapLink($this->meeting->start_latitude, $this->meeting->start_longitude, 'Start').' '.getGoogleMapLink($this->meeting->end_latitude, $this->meeting->end_longitude, 'End') : '',
            // 'customer_name' => $this->firstname.' '.$this->lastname,
            'order_status' => $this->orderstatus->order_status_name ?? '',
            'total_amount' => config('constants.currency_symbol').' '.number_format($this->grand_total, 2),
            'total_amount_no' => $this->grand_total,
            'total_quantity' => $this->total_quantity,
            'order_date' => $this->created_at->format(config('constants.DATE_FORMATE')),
            'order_pdf' => asset('storage/'.$this->order_pdf),
        ];
    }
}
