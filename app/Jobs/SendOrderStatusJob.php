<?php

namespace App\Jobs;

use App\Mail\OrderStatusAdmin;
use App\Mail\OrderStatusCustomer;
use App\Models\Order;
use App\Traits\InventoryTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use InventoryTrait;

    protected $orderId;

    protected $statusId;

    protected $comment;

    /**
     * Create a new job instance.
     */
    public function __construct($orderId, $statusId, $comment)
    {
        $this->orderId = $orderId;
        $this->statusId = $statusId;
        $this->comment = $comment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $orderData = Order::select('id', 'order_no', 'email', 'orderstatus_id', 'firstname', 'lastname')->where('id', $this->orderId)->with(['orderproduct', 'orderstatus:id,order_status_name'])->first();

        if ($this->statusId === 4) {

            $responce = app('order.service')->cancelOrder($orderData->order_no);

        } else {
            // send email to Customer
            Mail::to($orderData->email)->send(new OrderStatusCustomer($orderData, $this->comment));

            // send email to Admin
            $adminEmails = explode(',', getSettingData('order_email'));
            foreach ($adminEmails as $eskey => $email) {
                Mail::to($email)->send(new OrderStatusAdmin($orderData, $this->comment));
            }
        }
    }
}
