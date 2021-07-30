<?php
namespace SuaMadeira\Model;

use SuaMadeira\Model\Conexao;
use PDO;

// require_once('conexao.php');
// require_once('bd.php');
class Usuario {

	public $cnpj;
	public $cpf;
	public $nome_completo;
	public $inscricao_estadual;
	public $nome_fantasia;
	public $razao_social;
	public $email;
	public $senha;
	public $telefone;
	public $cep;
	public $endereco;
	public $endereco_numero;
	public $complemento;
	public $bairro;
	public $cidade;
	public $id;
	public $hash_troca_senha;


	public function inserir() {
		try {
			$db = Conexao::getInstance();

			$sql = 'INSERT INTO usuarios (
						cnpj,
						cpf,
						nome_completo,
						inscricao_estadual,
						nome_fantasia,
						razao_social,
						email,
						senha,
						telefone,
						cep,
						endereco,
						endereco_numero,
						complemento,
						bairro,
						cidade,
						uf,
						dt_criacao
					) VALUES 
					(
						:cnpj,
						:cpf,
						:nome_completo,
						:inscricao_estadual,
						:nome_fantasia,
						:razao_social,
						:email,
						:senha,
						:telefone,
						:cep,
						:endereco,
						:endereco_numero,
						:complemento,
						:bairro,
						:cidade,
						:uf,
						:dt_criacao
					)';

			$stm = $db->prepare($sql);

			$stm->bindParam(':cnpj', $this->cnpj);
			$stm->bindParam(':razao_social', $this->razao_social);
			$stm->bindParam(':nome_completo', $this->nome_completo);

			$stm->bindParam(':cnpj', $this->cnpj);
			$stm->bindParam(':cpf', $this->cpf);
			$stm->bindParam(':nome_completo', $this->nome_completo);
			$stm->bindParam(':inscricao_estadual', $this->inscricao_estadual);
			$stm->bindParam(':nome_fantasia', $this->nome_fantasia);
			$stm->bindParam(':razao_social', $this->razao_social);
			$stm->bindParam(':email', $this->email);
			$stm->bindParam(':senha', $this->senha);
			$stm->bindParam(':telefone', $this->telefone);
			$stm->bindParam(':cep', $this->cep);
			$stm->bindParam(':endereco', $this->endereco);
			$stm->bindParam(':endereco_numero', $this->endereco_numero);
			$stm->bindParam(':complemento', $this->complemento);
			$stm->bindParam(':bairro', $this->bairro);
			$stm->bindParam(':cidade', $this->cidade);
			$stm->bindParam(':uf', $this->uf);
			$stm->bindParam(':dt_criacao', $this->dt_criacao);
			
			$stm->execute();
			
			return $db->lastInsertId();

		} catch (Exception $e) {
			$stm->debugDumpParams();
			die($e->getMessage());
		}
	}
	
	public function insereHashTrocaSenha() {
		try {
			$db = Conexao::getInstance();

			$sql = 'UPDATE
						usuarios 
					SET
						hash_troca_senha=:hash_troca_senha
					WHERE
						id=:id';

			$stm = $db->prepare($sql);

            $data = array(
				'hash_troca_senha' => $this->hash_troca_senha
			);

			$data = array_merge($data, array('id' => $this->id));

			return $stm->execute($data);
			
		} catch (Exception $e) {
            $stm->debugDumpParams();
			print $e->getMessage();
		}
	}	

	public function trocaSenha() {
		try {
			$db = Conexao::getInstance();

			$sql = 'UPDATE
						usuarios 
					SET
						hash_troca_senha=:hash_troca_senha,
						senha=:senha
					WHERE
						id=:id';

			$stm = $db->prepare($sql);

            $data = array(
				'hash_troca_senha' => '',
				'senha' => $this->senha
			);

			$data = array_merge($data, array('id' => $this->id));

			return $stm->execute($data);
			
		} catch (Exception $e) {
            $stm->debugDumpParams();
			print $e->getMessage();
		}
	}	

	public function editar() {
		try {
			$db = Conexao::getInstance();

			$sql_senha = '';
			$arrSenha = [];
			if (!is_null($this->senha)) {
				$sql_senha = ',senha=:senha';
				$arrSenha = array('senha' => $this->senha);
			}

			$sql = 'UPDATE
						usuarios 
					SET
						cnpj=:cnpj,
						cpf=:cpf,
						nome_completo=:nome_completo,
						inscricao_estadual=:inscricao_estadual,
						nome_fantasia=:nome_fantasia,
						razao_social=:razao_social,
						email=:email,
						telefone=:telefone,
						cep=:cep,
						endereco=:endereco,
						endereco_numero=:endereco_numero,
						complemento=:complemento,
						bairro=:bairro,
						cidade=:cidade,
						uf=:uf
					'.$sql_senha.'
					WHERE
						id=:id';

			$stm = $db->prepare($sql);

            $data = array(
				'cnpj' => $this->cnpj,
				'cpf' => $this->cpf,
				'nome_completo' => $this->nome_completo,
				'inscricao_estadual' => $this->inscricao_estadual,
				'nome_fantasia' => $this->nome_fantasia,
				'razao_social' => $this->razao_social,
				'email' => $this->email,
				'telefone' => $this->telefone,
				'cep' => $this->cep,
				'endereco' => $this->endereco,
				'endereco_numero' => $this->endereco_numero,
				'complemento' => $this->complemento,
				'bairro' => $this->bairro,
				'cidade' => $this->cidade,
				'uf' => $this->uf
			);

			$data = array_merge($data, $arrSenha, array('id' => $this->id));
			
			return $stm->execute($data);
			
		} catch (Exception $e) {
            $stm->debugDumpParams();
			print $e->getMessage();
		}
	}	
	
	function dados() {
		return 		
			'{
				"cnpj": {
					"obrigatorio":"true",
					"mensagem":"Informe o CNPJ"
				},
				"inscricao_estadual": {
					"obrigatorio":"true",
					"mensagem":"Informe a inscrição estadual"
				},
				"razao_social": {
					"obrigatorio":"true",
					"mensagem":"Preencha a razão social"
				},
				"nome_fantasia": {
					"obrigatorio":"true",
					"mensagem":"Preencha o nome fantasia"
				},
				"nome_completo": {
					"obrigatorio":"true",
					"mensagem":"Preencha seu nome completo"
				},
				"email": {
					"obrigatorio":"true",
					"mensagem":"Preencha seu endereço de e-mail"
				},
				"senha": {
					"obrigatorio":"true",
					"mensagem":"Insira uma senha"
				},
				"senha2": {
					"obrigatorio":"true",
					"mensagem":"Insira a confirmação da senha"
				},
				"telefone": {
					"obrigatorio":"true",
					"mensagem":"Informe um número de telefone"
				},
				"cep": {
					"obrigatorio":"true",
					"mensagem":"Insira o seu CPF"
				},
				"endereco": {
					"obrigatorio":"true",
					"mensagem":"Insira um endereço válido"
				},
				"endereco_numero": {
					"obrigatorio":"false",
					"mensagem":""
				},
				"complemento": {
					"obrigatorio":"false",
					"mensagem":""
				},
				"bairro": {
					"obrigatorio":"true",
					"mensagem":"Insira o bairro"
				},
				"cidade": {
					"obrigatorio":"true",
					"mensagem":"Insira a cidade"
				},
				"uf": {
					"obrigatorio":"true",
					"mensagem":"Insira o estado (UF)"
				}
			}';
	}
	
	public function autenticaUsuario() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						id,
						email,
						senha,
						nome_completo,
						tipo_pessoa,
						grupo
					FROM
						usuarios
					WHERE
						email = :email
					';
					

			$stm = $db->prepare($sql);
			
			$stm->execute(array(':email' => $this->email));
			
			return $stm->fetchAll();
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}

	public function buscaUsuarioHash() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						id,
						email,
						senha,
						nome_completo
					FROM
						usuarios
					WHERE
						hash_troca_senha = :hash_troca_senha
					';
					

			$stm = $db->prepare($sql);
			
			$stm->execute(array(':hash_troca_senha' => $this->hash_troca_senha));
			
			return $stm->fetch(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}	
	
	public function buscaUsuarioExiste() {
		try {
			$db = Conexao::getInstance();
			
			$where = '';
			$condicional = '';
			$arrWhereFiltro = [];
			if ($this->email != null) {
				$where .= sprintf('%s email = :email', $condicional);
				$condicional = 'AND';
				$arrWhereFiltro[':email'] = $this->email;
			}

			if ($this->cnpj != null) {
				$where .= sprintf('%s cnpj = :cnpj', $condicional);
				$condicional = 'AND';
				$arrWhereFiltro[':cnpj'] = $this->cnpj;
			}
			
			if ($this->inscricao_estadual != null) {
				$where .= sprintf('%s inscricao_estadual = :inscricao_estadual', $condicional);
				$condicional = 'AND';
				$arrWhereFiltro[':inscricao_estadual'] = $this->inscricao_estadual;
			}			

			$sql = 'SELECT
						id,
						nome_fantasia,
						email,
						cnpj,
						inscricao_estadual
					FROM
						usuarios
					WHERE
					'.$where;

			$stm = $db->prepare($sql);
			
			$stm->execute($arrWhereFiltro);

			return $stm->fetch(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}	

	public function buscaUsuario() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						cnpj,
						cpf,
						nome_completo,
						inscricao_estadual,
						nome_fantasia,
						razao_social,
						email,
						telefone,
						cep,
						endereco,
						endereco_numero,
						complemento,
						bairro,
						cidade,
						uf						
					FROM
						usuarios
					WHERE
						id = :id
					';

			$stm = $db->prepare($sql);
			
			$stm->execute([
							':id' => $this->id
						]);
			
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}	
}
?>
