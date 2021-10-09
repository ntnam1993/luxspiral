<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $title = $notifiable->name.'様';
        return (new MailMessage)
            ->subject('【遊Phone】ログインパスワード再設定の確認')
            ->line($title)
            ->line('遊PhoneCMS ログインパスワード再設定方法についてお知らせいたします。')
            ->line('──────────────────────────────')
            ->line('◆パスワードの再設定について')
            ->line('──────────────────────────────')
            ->line('下記のアドレスにアクセスしますと、パスワードの再設定をおこなえます。')
//            ->action('Reset Password', url('password/reset', $this->token))
            ->line(url('password/reset', $this->token).'?email='.$notifiable->email)
            ->line('※上記のアドレスは24時間経過いたしますと無効になります。')
            ->line('パスワード再発行の手続きにお心当たりの無い場合は')
            ->line('このメールを破棄していただきますようお願い申し上げます。')
            ->line('──────────────────────────────')
            ->line('※本メールにお心あたりがない場合は、お手数ですが')
            ->line('このままメールを破棄いただきますようお願いします。')
            ->line('※このメールアドレスは送信専用です。返信はおこなっておりません');
    }
}
