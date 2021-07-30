<?php
require_once('conexao.php');
require_once('bd.php');
class Cliente {
	public $CodigoCliente = '';
	public $RazaoSocial = '';
	public $Nomefantasia = '';
	public $Endereco = '';
	public $Complemento = '';
	public $Bairro = '';
	public $CEP = '';
	public $Cidade = '';
	public $Estado = '';
	public $CaixaPostal = '';
	public $CGC = '';
	public $InscricaoEstadual = '';
	public $Telefone1 = '';
	public $Telefone2 = '';
	public $Ramal = '';
	public $Fax = '';
	public $EnderecoCobranca = '';
	public $ComplementoCobranca = '';
	public $BairroCobranca = '';
	public $CEPCobranca = '';
	public $CidadeCobranca = '';
	public $EstadoCobranca = '';
	public $EnderecoEntrega = '';
	public $ComplementoEntrega = '';
	public $BairroEntrega = '';
	public $CEPEntrega = '';
	public $CidadeEntrega = '';
	public $EstadoEntrega = '';
	public $EMail = '';
	public $Contato = '';
	public $DataAniversario = '';
	public $DataCadastro = '';
	public $Observacoes = '';
	public $TipoCliente = '';
	public $Cd_Base_Cgc_Cpf = '';
	public $Cd_Filial_Cgc = '';
	public $Cd_Digito_Cgc_Cpf = '';
	public $nom_tabela = 'Clientes';

	
	public function __construct() {
		$this->CodigoCliente = '';
		$this->RazaoSocial = '';
		$this->Nomefantasia = '';
		$this->Endereco = '';
		$this->Complemento = '';
		$this->Bairro = '';
		$this->CEP = '';
		$this->Cidade = '';
		$this->Estado = '';
		$this->CaixaPostal = '';
		$this->CGC = '';
		$this->InscricaoEstadual = '';
		$this->Telefone1 = '';
		$this->Telefone2 = '';
		$this->Ramal = '';
		$this->Fax = '';
		$this->EnderecoCobranca = '';
		$this->ComplementoCobranca = '';
		$this->BairroCobranca = '';
		$this->CEPCobranca = '';
		$this->CidadeCobranca = '';
		$this->EstadoCobranca = '';
		$this->EnderecoEntrega = '';
		$this->ComplementoEntrega = '';
		$this->BairroEntrega = '';
		$this->CEPEntrega = '';
		$this->CidadeEntrega = '';
		$this->EstadoEntrega = '';
		$this->EMail = '';
		$this->Contato = '';
		$this->DataAniversario = '';
		$this->DataCadastro = '';
		$this->Observacoes = '';
		$this->TipoCliente = '';
		$this->Cd_Base_Cgc_Cpf = '';
		$this->Cd_Filial_Cgc = '';
		$this->Cd_Digito_Cgc_Cpf = '';	
	}
	
	public function listarTodos($pagina_atual = 0,$linha_inicial = 0,$coluna = '',$buscar = '', $quantidade = '', $ordem = '') {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$where = '';
		
		if (!empty($coluna) && (!empty($buscar)) ) {
			$where = sprintf(' WHERE %s = "%s" ',$coluna,$buscar);
			if ($coluna !== 'CodigoCliente') {
				$where = sprintf(' WHERE UPPER(%s) LIKE "%s%%" ',$coluna,strtoupper($buscar));
			}
		}
		
		$paginacao = " LIMIT " . QTDE_REGISTROS;
		$qtd_registros = QTDE_REGISTROS;
		if ($quantidade > 0) {
            $qtd_registros = $quantidade;
        }
		if ( $pagina_atual > 0) {
			$paginacao = ' LIMIT '.$qtd_registros;
			if ($pagina_atual > 0 && $linha_inicial > 0) {
				$paginacao = " LIMIT $qtd_registros OFFSET ".($linha_inicial);
			}
		}
		
		if ($ordem == '') {
            $ordem = 'RazaoSocial ASC, NomeFantasia ASC';
		}
		
		$sql = "SELECT *, (SELECT COUNT(Cd_Orcamento) FROM `Orcamento` WHERE Orcamento.Cd_Cliente = Clientes.CodigoCliente ) as qtdUso FROM ".$this->nom_tabela.$where." ORDER BY ".$ordem.$paginacao;
		
		// echo $sql;exit;
		
		$dados = $crud->getSQLGeneric($sql);
		return $dados;
		
		//
	}

	public function listarTodosTotal($coluna = '',$buscar = '') {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$where = '';
		
		if (!empty($coluna) && (!empty($buscar) || $buscar >= 0) ) {
			$where = sprintf(' WHERE %s = "%s" ',$coluna,$buscar);
			if ($coluna !== 'CodigoCliente') {
				$where = sprintf(' WHERE UPPER(%s) LIKE "%s%%" ',$coluna,strtoupper($buscar));
			}
		}		
		
		$sql = "SELECT count(*) as total_registros FROM ".$this->nom_tabela.$where;		
		
		$dados = $crud->getSQLGeneric($sql,null,FALSE);		
		
		return $dados->total_registros;
		
		//
	}	
	
	public function listarCliente($handle) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "SELECT * FROM ".$this->nom_tabela." WHERE CodigoCliente = ?";
		$arrayParam = array($handle); 
		
		$dados = $crud->getSQLGeneric($sql,$arrayParam, TRUE);
		
		return $dados;
		
		//
	}	
	
	public function editarCliente($post) {
		$pdo = Conexao::getInstance();
		
		$arrayCliente = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'CodigoCliente')
				$arrayCliente[$key] =  $value;
		}
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$arrayCond = array('CodigoCliente=' => $post['handle']);  
		$retorno   = $crud->update($arrayCliente, $arrayCond);  		
		
		return $retorno;
	}
	
	public function cadastrarCliente($post) {
		$pdo = Conexao::getInstance();
		
		$arrayCliente = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'CodigoCliente')
				$arrayCliente[$key] =  $value;
		}		
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);

		$retorno   = $crud->insert($arrayCliente);  		
		
		return $retorno;
		exit;
	}

	public function excluir($handle) {
		$pdo = Conexao::getInstance();
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		$crud->delete(array('CodigoCliente' => $handle));
	}	
}
?>
