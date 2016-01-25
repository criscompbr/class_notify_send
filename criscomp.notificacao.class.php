<?php

/**
 * Notify-send
 *
 * PHP version 5
 *
 * @category  PHP
 * @author    Cristiano Thomas <criscompbr@gmail.com>
 * @copyright 2016 Criscomp
 * @link      https://github.com/criscompbr/class_notify_send/
 * 
 */
class criscomp_notify {

	/**
	 *
	 * @var string
	 */
	protected $usuario = "";

	/**
	 * Notificação tempo de limite em milissegundos.
	 *
	 * @var integer
	 */
	protected $timeout = 3000;

	/**
	 * Caminho para notify-send comando.
	 *
	 * @var string
	 */
	protected $path = 'notify-send';

	/**
	 * Mostrar "ok, tudo bem" mensagens.
	 *
	 * @var boolean
	 */
	protected $mostraOK = false;

	/**
	 * Versão de instalado notify-send executável.
	 *
	 * @var string
	 */
	protected $versao = "0.7.6";

	/**
	 * Coleta numero do PID que está sendo executado.
	 *
	 * @var integer
	 */
	protected $pid = null;

	/**
	 * Carregar configuração.
	 */
	public function __construct($usuario = null) {
		header('Content-type: text/html; charset=UTF-8');

		if ($usuario) {
			$this->usuario = $usuario;

			// Verificar se é linux
			if (!$this->_checar_sistema_so()) {
				die('Utilize apena linux (ubuntu ou debian) e não windows');
			}

			// Verificar o monitor com desktop
			if (!$this->_checar_monitor()) {
				die('Por favor utilize o monitor com desktop e não modo terminal como servidor, tem que ser cliente com desktop.');
			}

			// Verificar se o programa está instalado
			if (!$this->_checa_programa()) {
				die('Por favor utilize no comando \'apt-get install notify-send\'');
			}

			// Verificar a versão do programa são atual ou nova versão
			if (!$this->_verificar_versao_programa()) {
				die('Por favor utilize no comando \'apt-get update || apt-get upgrade\'');
			}

			// Para fazer um teste pra exibir uma mensagem caso não funcione por favor reportar pelo criscompbr@gmail.com
			if ($this->mostraOK) {
				$this->_verificar_notificacao();
			}
		} else {
			die('Por favor preenche o usuário por exemplo "$notificacao = new criscomp_notify(\'cristiano\')"');
		}
	}

	protected function _checar_sistema_so() {
		if (DIRECTORY_SEPARATOR == "/") {
			return true;
		}
		return false;
	}

	protected function _checar_monitor() {
		exec("sudo su {$this->usuario} -c 'xrandr -d :0'", $saidas, $e);

		if ($e > 0) {
			throw new Exception(implode("\n", $saidas));
		}

		foreach ($saidas as $chave => $saida) {
			$saida = trim($saida);
			$saida = preg_replace('/\(/', '', $saida);
			$saida = preg_replace('/\s+/', ' ', $saida);

			if (preg_match("/connected/", $saida)) {
				return true;
				break;
			}
		}
		return false;
	}

	protected function _checa_programa() {
		exec("which notify-send > /dev/null", $o, $r);
		// Se existe o programa então retorna como 1
		if ($r > 0) {
			// Se não existe então verificar dpkg
			return (int) !!(int) shell_exec("sudo dpkg-query -l | grep notify-send | wc -l");
		}
		return 1;
	}

	protected function _verificar_versao_programa() {
		exec("notify-send --version", $o, $r);

		$a = explode(" ", $o[0]);
		if (version_compare($this->versao, $a[1], '>=') === true) {
			return true;
		} else {
			return false;
		}
	}

	protected function _get_pid() {
		exec("ps aux | awk '/notify-osd/ && !/awk/ {print $6}'", $ret);
		if (!empty($ret)) {
			return $ret[0];
		}
		return 0;
	}

	protected function _verificar_notificacao() {
		if ($this->_anti_flood_notify()) {
			exec("sudo su {$this->usuario} -c 'DISPLAY=:0 notify-send -i info \"Sistema em teste:\" \"Testado, com sucesso\"'");
			echo "Enviado com sucesso";
		}
	}

	protected function _anti_flood_notify() {
		$maximo = 13;
		$dataAtual = date("Y-m-d H:i:s");
		exec("cat /tmp/criscomp-notify", $retorno);
		if (!$retorno) {
			exec("sudo echo {$dataAtual} > /tmp/criscomp-notify");
			return true;
		} else {
			$dtAtual = date("Y-m-d H:i:s", strtotime($retorno[0]));
			$dtFuturo = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s")));
			$dtTimeAtual = new DateTime($dtAtual);
			$retComp = $dtTimeAtual->diff(new DateTime($dtFuturo));
			if ($retComp->s >= $maximo) {
				exec("sudo echo {$dataAtual} > /tmp/criscomp-notify");
				return true;
			} else {
				$faltam = $maximo - $retComp->s;
				die('Não pode usar flood, só aguarde ' . $faltam . ' segundo(s).');
				return false;
			}
		}
	}

	/**
	 * 
	 * @param type $titulo escreve titulo para exibir notificação
	 * @param type $mensagem escreve mensagem para exibir notificação
	 * @param type $icone ex.: error ou info ou /usr/share/icons/ tem tudo icones
	 * @param type $tempo tempo que iriá desaparece notificação, padrão é 3000 ( milisegundos )
	 */
	public function enviarNotificacao($titulo, $mensagem, $icone = null, $tempo = null) {
		if ($titulo and $mensagem) {
			$cmd = ' --category dev.validate';
			$cmd .= ' -h int:transient:1';
			$cmd .= ' -t ' . ($tempo ? $tempo : $this->timeout);
			$cmd .= ' -a criscomp';
			if ($icone) {
				$cmd .= ' -i ' . $icone;
			}
			$cmd .= ' "' . $titulo . '"';
			$cmd .= ' "' . $mensagem . '"';

			exec("sudo su {$this->usuario} -c 'DISPLAY=:0 notify-send {$cmd}'");
		}
	}

}
