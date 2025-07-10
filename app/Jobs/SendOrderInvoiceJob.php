<?php

namespace App\Jobs;

use App\Mail\OrderInvoiceAdmin;
use App\Mail\OrderInvoiceSend;
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
use Illuminate\Support\Facades\Storage;
use PDF;

class SendOrderInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use InventoryTrait;

    protected $orderId;

    protected $rootPath;

    /**
     * Create a new job instance.
     */
    public function __construct($orderId)
    {
        $this->orderId = $orderId;
        $this->rootPath = storage_path('app/public').'/invoices';
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $orderData = Order::where('id', $this->orderId)->with(['orderproduct', 'billingaddress'])->first();
        /*$pdfName = $orderData->invoice_no.'.pdf';

        $pdf = PDF::loadView('pdf.invoice', ['order_data' => $orderData, 'shippingbillingaddress' => $orderData->billingaddress]);
        $pdfContent = $pdf->download()->getOriginalContent();

        $client = Storage::createLocalDriver(['root' => $this->rootPath]);
        $client->put($pdfName, $pdfContent);
        $orderData->update(['orderstatus_id' => 2, 'is_paid' => 1, 'order_pdf' => 'invoices/'.$pdfName]);*/

        $orderProducts = $orderData->orderproduct;
        if ($orderProducts) {
            foreach ($orderProducts as $product) {
                if ($product->variant_id !== null) {
                    $this->stockLog($product->product_id, $product->variant_id, -$product->product_quantity, 0, 'Place Order #'.$orderData->order_no);
                    ProductVariant::where('id', $product->variant_id)->decrement('qty', $product->product_quantity);
                } else {
                    // Product qty Log stock change
                    $this->stockLog($product->product_id, null, -$product->product_quantity, 0, 'Place Order #'.$orderData->order_no);
                    Product::where('id', $product->product_id)->decrement('qty', $product->product_quantity);
                }
            }
        }

        // send email for Invoice
        $invoiceData = ['order_data' => $orderData, 'shippingbillingaddress' => $orderData->billingaddress];
        Mail::to($orderData->email)->send(new OrderInvoiceSend([$invoiceData]));

        // send email for Invoice to Admins
        $adminEmails = explode(',', getSettingData('order_email'));

        foreach ($adminEmails as $eskey => $email) {
            Mail::to($email)->send(new OrderInvoiceAdmin([$invoiceData]));
        }
    }
}
