<?php
require_once('conexao.php');
require_once('bd.php');
class Situacao {
	public $idSituacao = '';
	public $descricao = '';
	public $nom_tabela = 'Situacao';

	
	public function __construct() {
		$this->idSituacao = '';
		$this->descricao = '';
	}
	
	public function listarTodos($pagina_atual = 0,$linha_inicial = 0,$coluna = '',$buscar = '') {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$where = '';
		
		if (!empty($coluna) && (!empty($buscar) || $buscar >= 0) ) {
			$where = sprintf(' WHERE %s LIKE "%%%s%%" ',$coluna,$buscar);
		}
		
		$paginacao = "";
		if ( ($pagina_atual > 0) && ($linha_inicial > 0)) {
			$paginacao = 'LIMIT '.QTDE_REGISTROS;
			if ($pagina_atual > 0 && $linha_inicial > 0) {
				$paginacao = " LIMIT {$linha_inicial}, ".QTDE_REGISTROS;
			}
		}
		
		$sql = "SELECT * FROM ".$this->nom_tabela.$where." ORDER BY idSituacao ".$paginacao;
		
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
	
	public function listarSituacao($handle) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "SELECT * FROM ".$this->nom_tabela." WHERE idSituacao = ?";
		$arrayParam = array($handle); 
		
		$dados = $crud->getSQLGeneric($sql,$arrayParam, TRUE);
		
		return $dados;
		
		//
	}	
	
	public function editarSituacao($post) {
		$pdo = Conexao::getInstance();
		
		$arraySituacao = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'idSituacao')
				$arraySituacao[$key] =  $value;
		}
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$arrayCond = array('idSituacao=' => $post['handle']);  
		$retorno   = $crud->update($arraySituacao, $arrayCond);  		
		
		return $retorno;
	}
	
	public function cadastrarSituacao($post) {
		$pdo = Conexao::getInstance();
		
		$arraySituacao = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'idSituacao')
				$arraySituacao[$key] =  $value;
		}		
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);

		$retorno   = $crud->insert($arraySituacao);  		
		
		return $retorno;
	}	
}
?>
