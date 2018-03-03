<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
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
            ->subject(t('email', 'Сброс пароля'))
            ->markdown('email')
            ->greeting(t('email', 'Здравствуйте!'))
            ->line(t('email', 'Вы получили это письмо по тому, что запросили сброс пароля для вашего аккаунта.'))
            ->action(t('email', 'Сбросить пароль'), url(config('app.url').route('password.reset', $this->token, false)))
            ->line(t('email', 'Если вы не запрашивали сброс пароля, просто игнорируйте это письмо.'));
    }
}
