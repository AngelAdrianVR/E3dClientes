<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuoteRequestNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $subject, public $concept, public $folio, public $module, public $url)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        if (app()->environment() === 'production') {
            return ['mail','database'];
        } else {
            return ['database'];
        }
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->markdown('emails.default-email-template', [
                'greeting' => '¡Hola!',
                'intro' => "$this->concept con folio '$this->folio' se ha creado pero es necesario llenar campos faltantes y corregir precio de productos. Por favor, dar seguimiento oportuno y terminar a tiempo.",
                'url' => $this->url,
                'salutation' => 'Saludos,',
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'description' => "$this->concept con folio '$this->folio' se ha creado pero es necesario llenar campos faltantes y corregir precio de productos. Por favor, dar seguimiento oportuno y terminar a tiempo.",
            'additional_info' => "",
            'module' => "$this->module",
        ];
    }
}
