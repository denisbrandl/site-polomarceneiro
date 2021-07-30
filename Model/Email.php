<?php
namespace SuaMadeira\Model;

use SuaMadeira\Model\Conexao;
use PDO;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Dotenv;

class Email {

	public $mail;
	public $remetente_nome = 'Polo Marceneiro';
	public $remetente_email = 'pedagoga.fabiola@pedagogafabiola.com.br';
	public $destinatario_nome;
	public $destinatario_email;
	public $assunto;
	public $mensagem;
	public $arrCc = [];
	public $arrBcc = [];
	public function __construct()
	{
		$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1).'/');
		$dotenv->load();
		$this->mail = new PHPMailer();
		$this->mail->SMTPDebug = 0;
		$this->mail->isSMTP();                                   
		$this->mail->Host       = $_ENV['EMAIL_HOST'];              
		$this->mail->SMTPAuth   = true;                          
		$this->mail->Username   = $_ENV['EMAIL_USUARIO'];            
		$this->mail->Password   = $_ENV['EMAIL_SENHA'];                      
		$this->mail->SMTPSecure = $_ENV['EMAIL_PROTOCOLO_SEGURANCA'];
		$this->mail->Port       = $_ENV['EMAIL_PORTA'];   		
	}

	public function enviar() {
		try {
			$this->mail->setFrom($this->remetente_email, $this->remetente_nome);
			$this->mail->addAddress($this->destinatario_email, $this->destinatario_nome);
			$this->mail->addReplyTo($this->remetente_email, $this->remetente_nome);
			if (count($this->arrCc) > 0) {
				foreach ($this->arrCc as $cc) {
					$this->mail->addCC($cc);	
				}
			}

			if (count($this->arrBcc) > 0) {
				foreach ($this->arrBcc as $bcc) {
					$this->mail->addCC($bcc);	
				}
			}

			$this->mail->isHTML(true);
			$this->mail->Subject = $this->assunto;
			$this->mail->Body    = $this->mensagem;
			$this->mail->AltBody = nl2br($this->mensagem);
			return $this->mail->send();
			
		} catch (Exception $e) {
			die(333);
			echo $e->errorMessage();
		} catch (\Exception $e) {
			die(444);
			echo $e->getMessage();
		}
	}
}
?>
