<?php

namespace App\Mail;

use App\Models\Customer;
use App\Models\CustomerMessageTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $template;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
        $this->template = CustomerMessageTemplate::getActive('email');
    }

    public function envelope(): Envelope
    {
        $subject = $this->template 
            ? $this->template->replaceVariables($this->template->subject, $this->customer)
            : 'Welcome to SkyraMart! 🎉';

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customer-welcome',
            with: [
                'template' => $this->template,
                'customer' => $this->customer,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}