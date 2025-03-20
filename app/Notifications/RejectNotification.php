<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RejectNotification extends Notification
{
    use Queueable;

    public function __construct(public $subject, public $concept, public $folio, public $module, public $url)
    {
        //
    }

    public function via(object $notifiable): array
    {
        if (app()->environment() === 'production') {
            return ['mail','database'];
        } else {
            return ['database'];
        }
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->markdown('emails.default-email-template', [
                'greeting' => 'Hola',
                'intro' => "$this->concept con folio/ID $this->folio se ha rechazado. Por favor, dar seguimiento oportuno con el cliente.",
                'url' => $this->url,
                'salutation' => 'Saludos,',
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'description' => "$this->concept con folio/ID $this->folio se ha rechazado. Por favor, dar seguimiento oportuno con el cliente.",
            'additional_info' => "",
            'module' => "$this->module",
        ];
    }
}
