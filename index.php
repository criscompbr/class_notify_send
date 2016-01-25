<?php

require_once './criscomp.notificacao.class.php';

// Exibir apena texto
//(new criscomp_notify('cristiano'))->enviarNotificacao("Criscomp exemplo", "Exemplo para exibir notificação");

// Exibir com icone, texto
//(new criscomp_notify('cristiano'))->enviarNotificacao("Criscomp exemplo", "Exemplo para exibir notificação", "info");

// Exibir com icone, texto e dura 5 segundos para desaparece
(new criscomp_notify('cristiano'))->enviarNotificacao("Criscomp exemplo", "Exemplo para exibir notificação", "info", 5000);