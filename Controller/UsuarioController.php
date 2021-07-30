<?php
namespace SuaMadeira;
use SuaMadeira\Model\Usuario;
use SuaMadeira\Model\Email;

class UsuarioController {

	public $hash = '!00CoViDn@0Us@M@sC@r@';
	public function __construct() {
	}
	
	public function inserir($request) {
		$usuario = new Usuario();

		$usuarioId = 0;
		if (isset($request['handle_usuario'])) {
			$usuarioId = $request['handle_usuario'];
			$usuario->id = $usuarioId;
			unset($request['handle_usuario']);
		}

		$validaFormulario = $this->validaDados($request, ['senha', 'senha2']);

		if ($validaFormulario['success'] == 'false') {
			echo json_encode(
					[
						'success' => 'false',
						'message' => implode('<br>', $validaFormulario['message'])
					]
				);
			return;			
		}		

		$request['cep'] = str_replace('-', '', $request['cep']);
		foreach ($request as $req_k => $req_v) {
			$usuario->{$req_k} = $req_v;
		}

		if (isset($request['senha']) && isset($request['senha2'])) {
			$usuario->senha = password_hash($usuario->senha, PASSWORD_DEFAULT);	
		}

		if ($usuarioId == 0) {
			$arrDadosUsuario = $usuario->buscaUsuarioExiste();
			
			if (count($arrDadosUsuario) > 0) {
				$dados_existentes = [];
				if ($arrDadosUsuario[0]['email'] == $usuario->email) {
					$dados_existentes[] = 'Email: ' . $usuario->email;
				}
				if ($arrDadosUsuario[0]['cnpj'] == $usuario->cnpj) {
					$dados_existentes[] = 'CNPJ: ' . $usuario->cnpj;
				}
				if ($arrDadosUsuario[0]['inscricao_estadual'] == $usuario->inscricao_estadual) {
					$dados_existentes[] = 'Inscrição estadual: ' . $usuario->inscricao_estadual;
				}			
				echo json_encode(
						[
							'success' => 'false',
							'message' => 'Já existe uma conta registrada para os estes dados: <br>' . implode("<br>", $dados_existentes)
						]
					);
				return;			
			}
			$usuario->dt_criacao = date('Y-m-d H:i:s');
			$usuarioId = $usuario->inserir();
		} else {
			$retorno = $usuario->editar();
		}
		
		if (isset($request['conteudo_arquivo']) && $request['conteudo_arquivo'] != '') {
			$data = $request['conteudo_arquivo'];
			if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
				$data = substr($data, strpos($data, ',') + 1);
				$type = strtolower($type[1]); // jpg, png, gif

				if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
					throw new \Exception('invalid image type');
				}
				$data = str_replace( ' ', '+', $data );
				$data = base64_decode($data);

				if ($data === false) {
					throw new \Exception('base64_decode failed');
				}
			} else {
				throw new \Exception('did not match data URI with image data');
			}
			file_put_contents("./imagens/".$usuarioId.".png", $data);
		}
		
		if ($usuarioId > 0 || $retorno == true) {
			echo json_encode(
					[
						'success' => 'true',
						'message' => 'Dados salvo com sucesso!'
					]
				);
		} else {
            echo json_encode(
				[
					'success' => 'false',
					'message' => 'Erro ao salvar seus dados!'
				]
			); 
		}
		return;
	}

	public function recuperarSenha($request) {
		if (!isset($request['email_recuperar']) && !filter_var($request['email_recuperar'], FILTER_VALIDATE_EMAIL) ) {
			echo json_encode(
				[
					'success' => 'false',
					'message' => 'Endereço de e-mail inválido'
					]
				); 
				return;
		}
			
		$usuario = new Usuario();
		$usuario->email = $request['email_recuperar'];
		$arrDadosUsuario = $usuario->buscaUsuarioExiste();

		if ($arrDadosUsuario == false) {
			echo json_encode(
					[
						'success' => 'false',
						'message' => 'Não foi encontrado nenhuma conta para o e-mail informado'
					]
				);
			return;			
		}
		
		$hash_troca_senha = hash('sha512', $arrDadosUsuario['cnpj'].$this->hash);

		$usuario->id = $arrDadosUsuario['id'];
		$usuario->hash_troca_senha = $hash_troca_senha;
		$usuario->insereHashTrocaSenha();

		$objEmail = new Email();
		$objEmail->destinatario_email = $arrDadosUsuario['email'];
		$objEmail->destinatario_nome = $arrDadosUsuario['nome_fantasia'];
		$objEmail->assunto = 'Recuperar senha - Polo Marceneiro';
		$objEmail->mensagem = sprintf(
			'<p>Olá %s</p>
			<p>Você ou alguém solicitou a troca de senha no site Polo Marceneiro</p>
			<p>Clique no link abaixo para providenciar a troca de senha</p>
			<p>http://localhost/troca-senha.php?hash=%s</p>
			<p>Se você não solicitou a troca de senha, <b>Favor desconsiderar esta mensagem.</b></p>
			<p><small>Polo Marceneiro - www.nomedoprojeto.com.br</small></p>
			',
			$arrDadosUsuario['nome_fantasia'],
			$hash_troca_senha
		);
		$retornoEmail = $objEmail->enviar();
		if ($retornoEmail == true) {
			echo json_encode(
				[
					'success' => 'true',
					'message' => 'Um e-mail foi enviado para você com instruções para troca de senha!'
				]
			);
			return;
		} else {
			echo json_encode(
				[
					'success' => 'false',
					'message' => 'Desculpe, mas houve um erro durante a recuperação da senha. Por favor tente novamente mais tarde, ou entre em contato conosco.'
				]
			);			
		}
		
	}

	public function trocarSenha($request) {
		if (!isset($request['senha']) || !isset($request['senha2'])) {
			echo json_encode(
				[
					'success' => 'false',
					'message' => 'Desculpe, mas as senhas enviadas são diferentes!'
				]
			);
			return false;
		}

		if ($request['senha'] != $request['senha2']) {
			echo json_encode(
				[
					'success' => 'false',
					'message' => 'Desculpe, mas as senhas enviadas são diferentes!'
				]
			);
			return false;
		}

		
		$hash = $request['hash'];
		$senha = password_hash($request['senha'], PASSWORD_DEFAULT);	

		$usuario = new Usuario();
		$usuario->hash_troca_senha = $hash;
		$arrConsultaUsuarioHash = $usuario->buscaUsuarioHash();

		if ($arrConsultaUsuarioHash === false) {
			echo json_encode(
				[
					'success' => 'false',
					'message' => 'Desculpe, houve um erro ao trocar a senha! <br>(Hash invalido)'
				]
			);	
			return false;		
		}

		$usuario->id = $arrConsultaUsuarioHash['id'];
		$usuario->senha = $senha;
		$usuario->trocaSenha();

		echo json_encode(
			[
				'success' => 'false',
				'message' => 'Senha alterada com sucesso!'
			]
		);	
		return false;			
	}

	public static function buscaUsuario($id_usuario) {
		$usuario = new Usuario();
		$usuario->id = $id_usuario;

		$arrUsuario = $usuario->buscaUsuario();

		if (count($arrUsuario) > 0) {
			// $arrMaterialImagem = $material->buscaMaterialImagem();
			// if (count($arrMaterialImagem) > 0) {
			// 	$arrMaterial['imagem'] = $arrMaterialImagem;
			// }
			echo json_encode(
				$arrUsuario[0]
			);
		}
	}	
	
	public function login($frmDados) {
		$usuario = new Usuario();
		
		$usuario->email = $frmDados['email'];
		
		$arrDadosUsuario = $usuario->autenticaUsuario();
		
		if (count($arrDadosUsuario) == 0) {
			echo json_encode(
					[
						'success' => 'false',
						'message' => 'Endereço de e-mail não encontrado'
					]
				);
			return;
		}
		
		if (password_verify($frmDados['senha'], $arrDadosUsuario[0]['senha']) == false) {
			echo json_encode(
					[
						'success' => 'false',
						'message' => 'Senha incorreta'
					]
				);
			return;
		}
		

		$_SESSION['user_id'] = $arrDadosUsuario[0]['id'];
		$_SESSION['username'] = $arrDadosUsuario[0]['nome_completo'];
		$_SESSION['grupo'] = $arrDadosUsuario[0]['grupo'];
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$user_browser = $_SERVER['HTTP_USER_AGENT'];
		$password = hash('sha512', $arrDadosUsuario[0]['senha'].$this->hash);
		$_SESSION['login_string'] = hash('sha512', $password.$ip_address.$user_browser);

		echo json_encode(
				[
					'success' => 'true',
					'message' => 'Dados autenticados com sucesso! Você será redirecionado para o painel.'
				]
			);		
		
		return false;
	}
	
	private function validaDados($frmDados, $arrIgnore) {
		$frm_valido = 'true';
		$arrRetorno = ['success' => true, 'message' => [] ];
		$usuario = new Usuario();

		$arrCampos = json_decode($usuario->dados(), true);

		foreach ($arrCampos as $key => $value) {
			if (in_array($key, $arrIgnore)) {
				continue;
			}
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
