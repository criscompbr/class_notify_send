##Tutorial como usar o class.notificacao.criscomp.php#

 * 1 Verificar se o "notify-osd" está instalado na sua máquina por exemplo:
  ![Verificar se o notify-osd está instalado ou não](http://45.55.95.172/github/notify_passo1.png)
  **Se não tem programa instalado então continua 1.1, se tem programa instalado então pule para 2**

 * 1.1 Digita no comando `~$: sudo apt-get update | apt-get install notify-osd`
  ![Comando para instalar o programa](http://45.55.95.172/github/notify_passo2.png)

 * 1.2 Fazer um teste no comando `~$: notify-send "Titulo" "Mensagem"` verificar se o dialogo iriá aparece uma mensagem já está funcionando com sucesso, caso não funcione por favor manda printscreen e envia por email criscompbr@gmail.com
  ![Fazer um teste e ver se funciona](http://45.55.95.172/github/notify_passo3.png)

 * 2 Verificar qual usuário que você utilizar por exemplo:
   No comando `who -m` ou `who am i` e mostra lista do nome e você pega login que você está usado por exemplo '**cristiano**'
  ![Verificar qual login que você está usado](http://45.55.95.172/github/notify_passo4.png)

 * 3 Abre o arquivo index.php e coloque exemplo:
`(new criscomp_notify('**cristiano**'))->enviarNotificacao("Criscomp exemplo", "Exemplo para exibir notificação");`
  ou pode usar script simples então utilize:
`$notify = new criscomp_notify('**cristiano**');
 $notify->enviarNotificacao("Criscomp exemplo", "Exemplo para exibir notificação");`
  ![Exemplo como funciona script](http://45.55.95.172/github/notify_passo5.png)


Caso não funciona então reportar pelo criscompbr@gmail.com
