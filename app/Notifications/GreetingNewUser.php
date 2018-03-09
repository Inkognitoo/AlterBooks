<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class GreetingNewUser extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(t('email', 'Спасибо за регистрацию!'))
            ->markdown('email')
            ->greeting(t('email', 'Здравствуйте!'))
            ->line(t('email', 'Вы получили это письмо по тому, что зарегистрировались на AlterBooks.'))
            ->line(t('email', 'Спасибо! Оставайтесь с нами и следите за новостями!'));
    }
}
