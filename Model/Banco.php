<?php
require_once('conexao.php');
require_once('bd.php');
class Banco {

	public $CodigoBanco;
	public $RazaoSocial;
	public $Agencia;
	public $DvAgencia;
	public $ContaCorrente;
	public $DvContaCorrente;
	public $Convenio;
	public $Carteira;
	public $Comando;
	public $EspecieTitulo;
	public $Instrucao1;
	public $Instrucao2;
	public $Aceite;
	public $JurosdeMora;
	public $nom_tabela = 'bancos';

	
	public function __construct() {
		$CodigoBanco = 0;
		$RazaoSocial = '';
		$Agencia = '';
		$DvAgencia = '';
		$ContaCorrente;
		$DvContaCorrente;
		$Convenio = '';
		$Carteira = '';
		$Comando = '';
		$EspecieTitulo;
		$Instrucao1 = '';
		$Instrucao2 = '';
		$Aceite = '';
		$JurosdeMora = '';	
	}
	
	public function listarTodos($pagina_atual = 0,$linha_inicial = 0,$coluna = '',$buscar = '') {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$where = '';
		if ($coluna != '' && $buscar != '') {
			$where = sprintf(' WHERE %s LIKE "%%%s%%" ',$coluna,$buscar);
		}
		
		$paginacao = '';
		if ($pagina_atual > 0 && $linha_inicial > 0) {
			$paginacao = " LIMIT {$linha_inicial}, ".QTDE_REGISTROS;
		}
		
		$sql = "SELECT * FROM ".$this->nom_tabela.$where." ORDER BY RazaoSocial ".$paginacao;
		
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
	
	public function listarBanco($handle) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "SELECT * FROM ".$this->nom_tabela." WHERE CodigoBanco = ?";
		$arrayParam = array($handle); 
		
		$dados = $crud->getSQLGeneric($sql,$arrayParam, TRUE);
		
		return $dados;
		
		//
	}	
	
	public function editarBanco($post) {
		$pdo = Conexao::getInstance();
		
		$arrayBanco = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'CodigoBanco')
				$arrayBanco[$key] =  $value;
		}
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$arrayCond = array('CodigoBanco=' => $post['handle']);  
		$retorno   = $crud->update($arrayBanco, $arrayCond);  		
		
		return $retorno;
	}
	
	public function cadastrarBanco($post) {
		$pdo = Conexao::getInstance();
		
		$arrayBanco = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'CodigoBanco')
				$arrayBanco[$key] =  $value;
		}		
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);

		$retorno   = $crud->insert($arrayBanco);  		
		
		return $retorno;
		exit;
	}	
}
?>
