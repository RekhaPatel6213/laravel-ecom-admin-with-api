<?php

namespace App\Jobs;

use App\Mail\OrderCancelAdmin;
use App\Mail\OrderCancelCustomer;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Traits\InventoryTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderCancelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use InventoryTrait;

    protected $orderId;

    /**
     * Create a new job instance.
     */
    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $orderData = Order::select('id', 'order_no', 'email')->where('id', $this->orderId)->with(['orderproduct'])->first();

        $orderProducts = $orderData->orderproduct;
        if ($orderProducts) {
            foreach ($orderProducts as $product) {
                if ($product->variant_id !== null) {
                    $this->stockLog($product->product_id, $product->variant_id, $product->product_quantity, 0, 'Cancel Order #'.$orderData->order_no);
                    ProductVariant::where('id', $product->variant_id)->increment('qty', (int) $product->product_quantity);
                } else {
                    // Product qty Log stock change
                    $this->stockLog($product->product_id, null, $product->product_quantity, 0, 'Cancel Order #'.$orderData->order_no);
                    Product::where('id', $product->product_id)->increment('qty', (int) $product->product_quantity);
                }
            }
        }

        // send email to Customer
        Mail::to($orderData->email)->send(new OrderCancelCustomer($orderData));

        // send email to Admin
        $adminEmails = explode(',', getSettingData('order_email'));
        foreach ($adminEmails as $eskey => $email) {
            Mail::to($email)->send(new OrderCancelAdmin($orderData));
        }
    }
}
