<?php
require_once('conexao.php');
require_once('bd.php');
class ComposicaoPreco {

	public $Código;
	public $VL_MARGEM_BRUTA;
	public $VL_PERDA_MEDIA;
	public $VL_PRECO_VIDRO_ANTI;
	public $VL_PRECO_VIDRO_COM;
	public $VL_PRECO_EUCATEX;
	public $VL_PRECO_CANTONEIRA;
	public $VL_PRECO_SUPORTE;
	public $VL_PRECO_RIGI_PONTAS;
	public $VL_PRECO_PREGO;
	public $VL_PRECO_GRAMPO;
	public $VL_PRECO_FITA_GOMADA;
	public $VL_QTD_VOLTAS;
	public $VL_ESPELHO;
	public $nom_tabela = 'Composicao_Preco';

	
	public function __construct() {
		$Código = '';
		$VL_MARGEM_BRUTA = '';
		$VL_PERDA_MEDIA = '';
		$VL_PRECO_VIDRO_ANTI = '';
		$VL_PRECO_VIDRO_COM = '';
		$VL_PRECO_EUCATEX = '';
		$VL_PRECO_CANTONEIRA = '';
		$VL_PRECO_SUPORTE = '';
		$VL_PRECO_RIGI_PONTAS = '';
		$VL_PRECO_PREGO = '';
		$VL_PRECO_GRAMPO = '';
		$VL_PRECO_FITA_GOMADA = '';
		$VL_QTD_VOLTAS = '';
		$VL_ESPELHO = '';
	}
	
	public function listarTodos() {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "SELECT * FROM ".$this->nom_tabela;		
		
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
	
	public function listarComposicaoPreco($handle) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "SELECT * FROM ".$this->nom_tabela." WHERE Código = ?";
		$arrayParam = array($handle); 
		
		$dados = $crud->getSQLGeneric($sql,$arrayParam, TRUE);
		
		return $dados;
		
		//
	}	
	
	public function editarComposicaoPreco($post) {
		$pdo = Conexao::getInstance();
		
		$arrayComposicaoPreco = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'Código')
				$arrayComposicaoPreco[$key] =  $value;
		}
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$arrayCond = array('Código=' => $post['handle']);  
		$retorno   = $crud->update($arrayComposicaoPreco, $arrayCond);  		
		
		return $retorno;
	}
	
	public function cadastrarComposicaoPreco($post) {
		$pdo = Conexao::getInstance();
		
		$arrayComposicaoPreco = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'Código')
				$arrayComposicaoPreco[$key] =  $value;
		}		
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);

		$retorno   = $crud->insert($arrayComposicaoPreco);  		
		
		return $retorno;
		exit;
	}	
}
?>
