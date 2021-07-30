<?php
require_once('conexao.php');
require_once('bd.php');
class Dominio {
	private $nom_tabela = 'Dominios';

	public function listarTodos($ds_dominio = "") {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$arrayParam = array();
		if (!empty($ds_dominio)) {
			$sql = "SELECT * FROM ".$this->nom_tabela." WHERE Ds_Dominio = ?";
			$arrayParam = array($ds_dominio); 					
		} else {
			$sql = "SELECT * FROM ".$this->nom_tabela;
		}
		
		$dados = $crud->getSQLGeneric($sql,$arrayParam, TRUE);
		
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
	
	public function listarDominio($handle) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "SELECT * FROM ".$this->nom_tabela." WHERE Cd_Identificacao = ?";
		$arrayParam = array($handle); 
		
		$dados = $crud->getSQLGeneric($sql,$arrayParam, TRUE);
		
		return $dados;
		
		//
	}	
	
	public function editarDominio($post) {
		$pdo = Conexao::getInstance();
		
		$arrayDominio = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' || $key != 'Cd_Identificacao')
				$arrayDominio[$key] =  $value;
		}
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$arrayCond = array('Cd_Identificacao=' => $post['handle']);  
		$retorno   = $crud->update($arrayDominio, $arrayCond);  		
		
		return $retorno;
	}
	
	public function cadastrarDominio($post) {
		$pdo = Conexao::getInstance();

		$arrayDominio = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'Cd_Identificacao')
				$arrayDominio[$key] =  $value;
		}		
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);

		$retorno   = $crud->insert($arrayDominio);  		
		
		return $retorno;
	}	
}
?>
