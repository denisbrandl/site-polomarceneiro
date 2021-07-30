<?php
require_once('conexao.php');
require_once('bd.php');
class FormaPagamento {

	private $CodigoFormaPagto;
	private $DescricaoFormaPagto;
	public $nom_tabela = 'FormasPagamento';

	
	public function __construct() {
		$CodigoFormaPagto = '';
		$DescricaoFormaPagto = '';	
	}
	
	public function listarTodos($pagina_atual,$linha_inicial) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "SELECT * FROM ".$this->nom_tabela." ORDER BY DescricaoFormaPagto LIMIT {$linha_inicial}, ".QTDE_REGISTROS;		
		
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
	
	public function listarFormaPagamento($handle) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "SELECT * FROM ".$this->nom_tabela." WHERE CodigoFormaPagto = ?";
		$arrayParam = array($handle); 
		
		$dados = $crud->getSQLGeneric($sql,$arrayParam, TRUE);
		
		return $dados;
		
		//
	}	
	
	public function editarFormaPagamento($post) {
		$pdo = Conexao::getInstance();
		
		$arrayFormaPagamento = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'CodigoFormaPagto')
				$arrayFormaPagamento[$key] =  $value;
		}
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$arrayCond = array('CodigoFormaPagto=' => $post['handle']);  
		$retorno   = $crud->update($arrayFormaPagamento, $arrayCond);  		
		
		return $retorno;
	}
	
	public function cadastrarFormaPagamento($post) {
		$pdo = Conexao::getInstance();
		
		$arrayFormaPagamento = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'CodigoFormaPagto')
				$arrayFormaPagamento[$key] =  $value;
		}		
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);

		$retorno   = $crud->insert($arrayFormaPagamento);  		
		
		return $retorno;
		exit;
	}	
}
?>
