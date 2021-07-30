<?php
require_once('conexao.php');
require_once('bd.php');
class Fornecedor {

	private $CodigoFornecedor;
	private $RazaoSocial;
	private $Endereco;
	private $Complemento;
	private $Bairro;
	private $CEP;
	private $Cidade;
	private $Estado;
	private $Pais;
	private $CGC;
	private $InscricaoEstadual;
	private $Telefone1;
	private $Telefone2;
	private $Ramal;
	private $Fax;
	private $EMail;
	private $Contato;
	private $DataAniversario;
	private $DataCadastro;
	private $Observacoes;
	public $nom_tabela = 'Fornecedores';
	private $order_by_default = ' RazaoSocial ';

	
	public function __construct() {
		$CodigoFornecedor = '';
		$RazaoSocial = '';
		$Endereco = '';
		$Complemento = '';
		$Bairro = '';
		$CEP = '';
		$Cidade = '';
		$Estado = '';
		$Pais = '';
		$CGC = '';
		$InscricaoEstadual = '';
		$Telefone1 = '';
		$Telefone2 = '';
		$Ramal = '';
		$Fax = '';
		$EMail = '';
		$Contato = '';
		$DataAniversario = '';
		$DataCadastro = '';
		$Observacoes = '';
	}
	
	public function listarTodos($pagina_atual = 0,$linha_inicial = 0,$coluna = '',$buscar = '', $quantidade = -1) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$where = '';
		if ($coluna != '' && $buscar != '') {
			$where = sprintf(' WHERE %s LIKE UPPER("%%%s%%") ',$coluna,strtoupper($buscar));
		}
		
		$paginacao = "";
		if ($quantidade > 0) {
			$paginacao = 'LIMIT '.QTDE_REGISTROS;
		}	
		if ($pagina_atual > 0 && $linha_inicial > 0) {
			$paginacao = " LIMIT {$linha_inicial}, ".QTDE_REGISTROS;
		}		
		
		$sql = "SELECT * FROM ".$this->nom_tabela.$where." ORDER BY ".$this->order_by_default.$paginacao;
		
		$dados = $crud->getSQLGeneric($sql);
		
		return $dados;
		
		//
	}

	public function listarTodosTotal() {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "SELECT count(*) as total_registros FROM ".$this->nom_tabela;		
		
		$dados = $crud->getSQLGeneric($sql,null,FALSE);		
		
		return $dados->total_registros;
		
		//
	}	
	
	public function listarProdutosFornecedor($handle) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "SELECT * FROM Produtos WHERE CodigoFornecedor = ?";
		$arrayParam = array($handle); 
		
		$dados = $crud->getSQLGeneric($sql,$arrayParam, TRUE);
		
		return $dados;	
	}
	
	public function listarFornecedor($handle) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "SELECT * FROM ".$this->nom_tabela." WHERE CodigoFornecedor = ?";
		$arrayParam = array($handle); 
		
		$dados = $crud->getSQLGeneric($sql,$arrayParam, TRUE);
		
		return $dados;
		
		//
	}	
	
	public function editarFornecedor($post) {
		$pdo = Conexao::getInstance();
		
		$arrayFornecedor = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'CodigoFornecedor')
				$arrayFornecedor[$key] =  $value;
		}
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$arrayCond = array('CodigoFornecedor=' => $post['handle']);  
		$retorno   = $crud->update($arrayFornecedor, $arrayCond);  		
		
		return $retorno;
	}
	
	public function cadastrarFornecedor($post) {
		$pdo = Conexao::getInstance();
		
		$arrayFornecedor = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'CodigoFornecedor')
				$arrayFornecedor[$key] =  $value;
		}		
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);

		$retorno   = $crud->insert($arrayFornecedor);  		
		
		return $retorno;
		exit;
	}	
}
?>
