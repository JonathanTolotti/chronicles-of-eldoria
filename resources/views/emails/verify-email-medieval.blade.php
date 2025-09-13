@component('mail::message')
# ⚔️ Chronicles of Eldoria

## Bem-vindo, Nobre Aventureiro!

Sua jornada épica está prestes a começar! Para ativar sua conta e desbloquear todos os segredos de Eldoria, você deve verificar seu endereço de email.

@component('mail::button', ['url' => $url, 'color' => 'success'])
🔮 Verificar Email e Começar Aventura
@endcomponent

---

**O que te aguarda em Eldoria:**
- 🏰 Crie seu personagem e escolha sua classe
- ⚔️ Enfrente desafios épicos e monstros lendários  
- 🏆 Conquiste glória e fama em batalhas épicas
- 👑 Torne-se uma lenda viva!

---

Se você não criou uma conta em Chronicles of Eldoria, pode ignorar este email com segurança.

**Que a sorte esteja ao seu lado, aventureiro!** ⚔️

@component('mail::subcopy')
Se você está tendo problemas para clicar no botão "Verificar Email", copie e cole a URL abaixo no seu navegador:

[{{ $url }}]({{ $url }})
@endcomponent

@endcomponent