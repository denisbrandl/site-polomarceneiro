<?php
require_once('conexao.php');
require_once('bd.php');
class UnidadeMedida {

	private $CodigoUnidadeMedida;
	private $DescricaoUnidadeMedida;
	private $NomeUnidadeMedida;
	public $nom_tabela = 'UnidadesMedida';
	private $order_by_default = 'DescricaoUnidadeMedida';	
	
	public function __construct() {
		$CodigoUnidadeMedida = '';
		$DescricaoUnidadeMedida = '';	
		$NomeUnidadeMedida = '';	
	}
	
	public function listarTodos($pagina_atual = 0,$linha_inicial = 0,$coluna = '',$buscar = '') {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$where = '';
		if ($coluna != '' && $buscar != '') {
			$where = sprintf(' WHERE %s LIKE UPPER("%%%s%%") ',$coluna,strtoupper($buscar));
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
	
	public function listarUnidadeMedida($handle) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "SELECT * FROM ".$this->nom_tabela." WHERE CodigoUnidadeMedida = ?";
		$arrayParam = array($handle); 
		
		$dados = $crud->getSQLGeneric($sql,$arrayParam, TRUE);
		
		return $dados;
		
		//
	}	
	
	public function editarUnidadeMedida($post) {
		$pdo = Conexao::getInstance();
		
		$arrayUnidadeMedida = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'CodigoUnidadeMedida')
				$arrayUnidadeMedida[$key] =  $value;
		}
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$arrayCond = array('CodigoUnidadeMedida=' => $post['handle']);  
		$retorno   = $crud->update($arrayUnidadeMedida, $arrayCond);  		
		
		return $retorno;
	}
	
	public function cadastrarUnidadeMedida($post) {
		$pdo = Conexao::getInstance();
		
		$arrayUnidadeMedida = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'CodigoUnidadeMedida')
				$arrayUnidadeMedida[$key] =  $value;
		}		
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);

		$retorno   = $crud->insert($arrayUnidadeMedida);  		
		
		return $retorno;
		exit;
	}	
}
?>
