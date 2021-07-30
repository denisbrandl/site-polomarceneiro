<?php
namespace SuaMadeira;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Dotenv;
use SuaMadeira\Model\Material;

class EmailController {
		
	public function __construct() {
	}
	
	public static function enviar($arrMensagem) {
		$validaFormulario = self::validaDados($arrMensagem);

		if ($validaFormulario['success'] == 'false') {
			echo json_encode(
					[
						'success' => 'false',
						'message' => implode('<br>', $validaFormulario['message'])
					]
				);
			return;			
		}
		
		if (!filter_var($arrMensagem['email'], FILTER_VALIDATE_EMAIL)) {			
			echo json_encode(
					[
						'success' => 'false',
						'message' => implode('<br>', array('Por favor informe um endereço de e-mail válido'))
					]
				);
			return;
		}
		
		$objMaterial = new Material();
		$objMaterial->id = $arrMensagem['handle_anuncio'];
		$arrMaterial = $objMaterial->buscaMaterial();
		
		$mail = new PHPMailer(true);
		$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1).'/');
		$dotenv->load();
		try {
			//Server settings
			$mail->SMTPDebug = 0;
			$mail->isSMTP();                                   
			$mail->Host       = $_ENV['EMAIL_HOST'];              
			$mail->SMTPAuth   = true;                          
			$mail->Username   = $_ENV['EMAIL_USUARIO'];            
			$mail->Password   = $_ENV['EMAIL_SENHA'];                      
			$mail->SMTPSecure = $_ENV['EMAIL_PROTOCOLO_SEGURANCA'];
			$mail->Port       = $_ENV['EMAIL_PORTA'];                           

			//Recipients
			$mail->setFrom('denisbr@gmail.com', 'Polo Marceneiro');
			$mail->addAddress($arrMaterial[0]['email'], $arrMaterial[0]['nome_fantasia']);
			$mail->addReplyTo($arrMensagem['email'], $arrMensagem['nome']);
			// $mail->addCC('cc@example.com');
			$mail->addBCC('denisbr@gmail.com');

			$mensagem = '<p>Olá, você recebeu uma nova mensagem do site Polo Marceneiro<p>';
			$mensagem .= 'Segue informações da mensagem: <br>';
			$mensagem .= 'Nome: ' . $arrMensagem['nome'];
			$mensagem .= '<br>Email: ' . $arrMensagem['email'];
			$mensagem .= '<br>Telefone: ' . $arrMensagem['telefone'].'</p>';
			$mensagem .= '<p>Mensagem: <br>'.$arrMensagem['mensagem'].'</p>';
			$mensagem .= '<p> Anúncio </p>';
			$mensagem .= 'Título: '.$arrMaterial[0]['titulo'].'<br>';
			$mensagem .= 'Marca: '.$arrMaterial[0]['marca'].'<br>';
			$mensagem .= 'Linha: '.$arrMaterial[0]['linha'].'<br>';
			$mensagem .= 'Cor: '.$arrMaterial[0]['cor'].'<br>';
			
			// Content
			$mail->isHTML(true);
			$mail->Subject = 'Nova mensagem do site Polo Marceneiro';
			$mail->Body    = $mensagem;
			$mail->AltBody = nl2br($mensagem);;

			$mail->send();
			
			echo json_encode(
					[
						'success' => 'true',
						'message' => 'Mensagem enviada com sucesso'
					]
				);
			return;			
			
		} catch (Exception $e) {
			echo json_encode(
					[
						'success' => 'false',
						'message' => 'Houve uma falha ao tentar enviar a mensagem! Por favor, tente novamente mais tarde'
					]
				);
			return;
		}		
	}
	
	private static function validaDados($frmDados) {
		$frm_valido = 'true';
		$arrRetorno = ['success' => true, 'message' => [] ];

		$arrDados = '{
						"nome": {
							"obrigatorio":"true",
							"mensagem":"Informe o seu nome"
						},
						"email": {
							"obrigatorio":"true",
							"mensagem":"Informe o seu e-mail"
						},
						"telefone": {
							"obrigatorio":"false",
							"mensagem":""
						},
						"mensagem": {
							"obrigatorio":"true",
							"mensagem":"Preencha uma mensagem"
						}
					}';

		$arrCampos = json_decode($arrDados, true);

		foreach ($arrCampos as $key => $value) {
			if (!isset($frmDados[$key]) || empty($frmDados[$key])) {
				if ($value['obrigatorio'] == 'true') {
					$arrRetorno['message'][] = $value['mensagem'];
					$frm_valido = 'false';
				}
			}
		}
		
		$arrRetorno['success'] = $frm_valido;
		
		return $arrRetorno;
		
	}	
}
?>
