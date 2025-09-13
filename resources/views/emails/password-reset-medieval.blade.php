@component('mail::message')
# 🛡️ Redefinição de Senha - Chronicles of Eldoria

Caro(a) Aventureiro(a),

Recebemos uma solicitação para redefinir a senha da sua conta em **Chronicles of Eldoria**.

@component('mail::button', ['url' => $resetUrl, 'color' => 'success'])
🔐 Redefinir Minha Senha
@endcomponent

**⚠️ Importante:**
- Este link expira em 60 minutos
- Se você não solicitou esta redefinição, ignore este email
- Sua senha atual permanecerá inalterada até que você crie uma nova

---

*"A verdadeira força de um herói não está apenas em suas armas, mas na sabedoria de proteger o que é valioso."*

**Que sua jornada continue com segurança!** ⚔️

@component('mail::subcopy')
Se você está tendo problemas para clicar no botão "Redefinir Minha Senha", copie e cole a URL abaixo no seu navegador:

[{{ $resetUrl }}]({{ $resetUrl }})
@endcomponent

@endcomponent
