<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class ResetPasswordMedieval extends ResetPasswordNotification
{
    use Queueable;

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $url = $this->resetUrl($notifiable);

        return (new MailMessage)
            ->subject('🛡️ Redefinição de Senha - Chronicles of Eldoria')
            ->greeting('Caro(a) Aventureiro(a),')
            ->line('Recebemos uma solicitação para redefinir a senha da sua conta em **Chronicles of Eldoria**.')
            ->action('🔐 Redefinir Minha Senha', $url)
            ->line('**⚠️ Importante:**')
            ->line('• Este link expira em 60 minutos')
            ->line('• Se você não solicitou esta redefinição, ignore este email')
            ->line('• Sua senha atual permanecerá inalterada até que você crie uma nova')
            ->line('')
            ->line('*"A verdadeira força de um herói não está apenas em suas armas, mas na sabedoria de proteger o que é valioso."*')
            ->line('')
            ->line('**Que sua jornada continue com segurança!** ⚔️')
            ->salutation('')
            ->line('Se você está tendo problemas para clicar no botão, copie e cole a URL abaixo no seu navegador:')
            ->line($url);
    }

    /**
     * Get the reset URL for the given notifiable.
     */
    protected function resetUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'password.reset',
            Carbon::now()->addMinutes(Config::get('auth.passwords.users.expire', 60)),
            [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]
        );
    }
}
