<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderInvoiceAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $order_data;

    public $shippingbillingaddress;

    /**
     * Create a new message instance.
     */
    public function __construct($result)
    {
        $this->order_data = $result[0]['order_data'];
        $this->shippingbillingaddress = $result[0]['shippingbillingaddress'];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: getSettingData('company_name').' : New Order #'.$this->order_data['order_no'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.new_order',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // return [Attachment::fromPath(realpath('storage/app/public/'.$this->order_data['order_pdf']))];

        // Check if 'order_pdf' is set and not empty
        if (empty($this->order_data['order_pdf'])) {
            \Log::error('Order PDF filename is empty.');

            return [];
        }

        // Construct the path using storage_path
        $orderPdfPath = storage_path('app/public/'.$this->order_data['order_pdf']);

        // Check if the file exists
        if (! file_exists($orderPdfPath)) {
            \Log::error('Order PDF file does not exist: '.$orderPdfPath);

            return [];
        }

        // Return the attachment
        return [Attachment::fromPath($orderPdfPath)];
    }
}
