<?php
require_once('conexao.php');
require_once('bd.php');
class Grupo {

	private $CodigoGrupo;
	private $DescricaoGrupo;
	public $nom_tabela = 'Grupos';
	private $order_by_default = 'DescricaoGrupo';

	
	public function __construct() {
		$CodigoGrupo = '';
		$DescricaoGrupo = '';	
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
	
	public function listarGrupo($handle) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "SELECT * FROM ".$this->nom_tabela." WHERE CodigoGrupo = ?";
		$arrayParam = array($handle); 
		
		$dados = $crud->getSQLGeneric($sql,$arrayParam, TRUE);
		
		return $dados;
		
		//
	}	
	
	public function editarGrupo($post) {
		$pdo = Conexao::getInstance();
		
		$arrayGrupo = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'CodigoGrupo')
				$arrayGrupo[$key] =  $value;
		}
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$arrayCond = array('CodigoGrupo=' => $post['handle']);  
		$retorno   = $crud->update($arrayGrupo, $arrayCond);  		
		
		return $retorno;
	}
	
	public function cadastrarGrupo($post) {
		$pdo = Conexao::getInstance();
		
		$arrayGrupo = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'CodigoGrupo')
				$arrayGrupo[$key] =  $value;
		}		
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);

		$retorno   = $crud->insert($arrayGrupo);  		
		
		return $retorno;
		exit;
	}	
}
?>
