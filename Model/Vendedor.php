<?php
require_once('conexao.php');
require_once('bd.php');
class Vendedor {

	private $CodigoVendedor;
	private $NomeVendedor;
	private $PercentualComissao;
	public $nom_tabela = 'Vendedores';
	private $order_by_default = 'NomeVendedor';	
	
	public function __construct() {
		$CodigoVendedor = '';
		$NomeVendedor = '';
		$PercentualComissao = '';	
	}
	
	public function listarTodos($pagina_atual = 0,$linha_inicial = 0,$coluna = '',$buscar = '') {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$where = '';
		if ($coluna != '' && $buscar != '') {
			$where = sprintf(' WHERE %s LIKE UPPER("%s%%") ',$coluna,strtoupper($buscar));
		}
		
		$paginacao = ' LIMIT '.QTDE_REGISTROS;
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
	
	public function listarVendedor($handle) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "SELECT * FROM ".$this->nom_tabela." WHERE CodigoVendedor = ?";
		$arrayParam = array($handle); 
		
		$dados = $crud->getSQLGeneric($sql,$arrayParam, TRUE);
		
		return $dados;
		
		//
	}	
	
	public function editarVendedor($post) {
		$pdo = Conexao::getInstance();
		
		$arrayVendedor = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'CodigoVendedor')
				$arrayVendedor[$key] =  $value;
		}
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$arrayCond = array('CodigoVendedor=' => $post['handle']);  
		$retorno   = $crud->update($arrayVendedor, $arrayCond);  		
		
		return $retorno;
	}
	
	public function cadastrarVendedor($post) {
		$pdo = Conexao::getInstance();
		
		$arrayVendedor = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'CodigoVendedor')
				$arrayVendedor[$key] =  $value;
		}		
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);

		$retorno   = $crud->insert($arrayVendedor);  		
		
		return $retorno;
		exit;
	}	
}
?>
