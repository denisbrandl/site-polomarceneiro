<?php
require_once('conexao.php');
require_once('bd.php');
class Afiliado {

	private $CodigoAfiliado;
	private $NomeAfiliado;
	private $PercentualComissao;
	public $nom_tabela = 'Afiliadoes';
	private $order_by_default = 'NomeAfiliado';	
	
	public function __construct() {
		$CodigoAfiliado = '';
		$NomeAfiliado = '';
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
	
	public function listarAfiliado($handle) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "SELECT * FROM ".$this->nom_tabela." WHERE CodigoAfiliado = ?";
		$arrayParam = array($handle); 
		
		$dados = $crud->getSQLGeneric($sql,$arrayParam, TRUE);
		
		return $dados;
		
		//
	}	
	
	public function editarAfiliado($post) {
		$pdo = Conexao::getInstance();
		
		$arrayAfiliado = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'CodigoAfiliado')
				$arrayAfiliado[$key] =  $value;
		}
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$arrayCond = array('CodigoAfiliado=' => $post['handle']);  
		$retorno   = $crud->update($arrayAfiliado, $arrayCond);  		
		
		return $retorno;
	}
	
	public function cadastrarAfiliado($post) {
		$pdo = Conexao::getInstance();
		
		$arrayAfiliado = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'CodigoAfiliado')
				$arrayAfiliado[$key] =  $value;
		}		
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);

		$retorno   = $crud->insert($arrayAfiliado);  		
		
		return $retorno;
		exit;
	}	
}
?>
