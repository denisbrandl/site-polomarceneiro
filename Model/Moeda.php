<?php
require_once('conexao.php');
require_once('bd.php');
class Moeda {

	private $CodigoMoeda;
	private $DescricaoMoeda;
	private $SiglaMoeda;
	private $Cotacao;
	private $DataAtualizacao;
	public $nom_tabela = 'Moedas';
	private $order_by_default = 'DescricaoMoeda';

	
	public function __construct() {
		$CodigoMoeda = '';
		$DescricaoMoeda = '';
		$SiglaMoeda = '';
		$Cotacao = '';
		$DataAtualizacao = '';
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
	
	public function listarMoeda($handle) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "SELECT * FROM ".$this->nom_tabela." WHERE CodigoMoeda = ?";
		$arrayParam = array($handle); 
		
		$dados = $crud->getSQLGeneric($sql,$arrayParam, TRUE);
		
		return $dados;
		
		//
	}	
	
	public function editarMoeda($post) {
		$pdo = Conexao::getInstance();
		
		$arrayMoeda = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'CodigoMoeda')
				$arrayMoeda[$key] =  $value;
		}
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$arrayCond = array('CodigoMoeda=' => $post['handle']);  
		$retorno   = $crud->update($arrayMoeda, $arrayCond);  		
		
		return $retorno;
	}
	
	public function cadastrarMoeda($post) {
		$pdo = Conexao::getInstance();
		
		$arrayMoeda = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'CodigoMoeda')
				$arrayMoeda[$key] =  $value;
		}		
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);

		$retorno   = $crud->insert($arrayMoeda);  		
		
		return $retorno;
		exit;
	}	
}
?>
