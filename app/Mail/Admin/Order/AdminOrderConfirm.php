<?php

namespace App\Mail\Admin\Order;

use App\Helper\SettingHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminOrderConfirm extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->afterCommit();
        $this->data = $data;
    }


    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        $subject = "";
        if ($this->data->order->user) {
            $subject = '[' . SettingHelper::get("name") . '] Order #' . $this->data->order->order_id . ' placed by ' . $this->data->order->user->fname . ' ' . $this->data->order->user->lname;
        } else {
            $subject = '[' . SettingHelper::get("name") . '] Order #' . $this->data->order->order_id . ' placed by someone';
        }
        return new Envelope(
            from: new Address(SettingHelper::get('email'), SettingHelper::get('name')),
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'mails.admin.order.confirm.order_confirm',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
