<?php
require_once('conexao.php');
require_once('bd.php');
class ProdutoAuxiliar {

	private $Cd_Prod_Aux;
	public $nom_tabela = 'Produtos_Auxiliares';
	private $order_by_default = 'Cd_Prod_Aux';	
	
	public function __construct() {
		$Cd_Prod_Aux = '';
	}
	
	public function listarTodos($pagina_atual = 0,$linha_inicial = 0,$coluna = '',$buscar = '') {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$where = '';
		if ($coluna != '' && $buscar != '') {
			$where = sprintf(' WHERE %s LIKE UPPER("%%%s%%") ',$coluna,strtoupper($buscar));
		}
		
// 		$paginacao = ' LIMIT '.QTDE_REGISTROS;
		$paginacao = '';
		if ($pagina_atual > 0 && $linha_inicial > 0) {
			$paginacao = " LIMIT {$linha_inicial}, ".QTDE_REGISTROS;
		}		
		
		$sql = "SELECT * FROM ".$this->nom_tabela.$where." ORDER BY ".$this->order_by_default.$paginacao;		
		$dados = $crud->getSQLGeneric($sql);
		return $dados;
	}

	public function listarTodosTotal() {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "SELECT count(*) as total_registros FROM ".$this->nom_tabela;		
		
		$dados = $crud->getSQLGeneric($sql,null,FALSE);		
		
		return $dados->total_registros;
		
		//
	}	
	
	public function listarProdutoAuxiliar($handle) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "SELECT * FROM ".$this->nom_tabela." WHERE Cd_Prod_Aux = ?";
		$arrayParam = array($handle); 
		$dados = $crud->getSQLGeneric($sql,$arrayParam, TRUE);
		
		return $dados;
		
		//
	}	
	
	public function editarProdutoAuxiliar($post) {
		$pdo = Conexao::getInstance();
		
		$arrayProdutoAuxiliar = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'Cd_Prod_Aux')
				$arrayProdutoAuxiliar[$key] =  $value;
		}
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$arrayCond = array('Cd_Prod_Aux=' => $post['handle']);  
		$retorno   = $crud->update($arrayProdutoAuxiliar, $arrayCond);  		
		
		return $retorno;
	}
	
	public function cadastrarProdutoAuxiliar($post) {
		$pdo = Conexao::getInstance();
		
		$arrayProdutoAuxiliar = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'Cd_Prod_Aux')
				$arrayProdutoAuxiliar[$key] =  $value;
		}		
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);

		$retorno   = $crud->insert($arrayProdutoAuxiliar);  		
		
		return $retorno;
		exit;
	}	
}
?>
