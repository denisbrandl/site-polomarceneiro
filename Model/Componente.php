<?php
require_once('conexao.php');
require_once('bd.php');
class Componente {
	public $idcomponente;
	public $descricao;
	public $custo;
	private $nom_tabela = 'COMPONENTES';

	public function listarTodos($pagina_atual = 0,$linha_inicial = 0,$coluna = '',$buscar = '',$order = '') {
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
		
		if (empty($order)) {
			$order = "DESCRICAO ASC";
		}
		
		$sql = "SELECT *, (SELECT COUNT(CD_ORCAMENTO) FROM `COMPONENTES_ITEM_ORCAMENTO` WHERE COMPONENTES_ITEM_ORCAMENTO.CD_COMPONENTE = COMPONENTES.IDCOMPONENTE ) as qtdUso FROM ".$this->nom_tabela.$where." ORDER BY ".$order.$paginacao;
		
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
	
	public function listarComponente($handle) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "SELECT * FROM ".$this->nom_tabela." WHERE IDCOMPONENTE = ?";
		$arrayParam = array($handle); 
		
		$dados = $crud->getSQLGeneric($sql,$arrayParam, TRUE);
		
		return $dados;
		
		//
	}	
	
	public function editarComponente($post) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		$arrayComponente = array('descricao' => $post['descricao'], 'custo' => $post['custo']); 
		$arrayCond = array('IDCOMPONENTE=' => $post['handle']);  
		$retorno   = $crud->update($arrayComponente, $arrayCond);  		
		
		return $retorno;
	}
	
	public function cadastrarComponente($post) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		$arrayComponente = array('descricao' => $post['descricao'], 'custo' => str_replace(",",".",$post['custo'])); 
		$retorno   = $crud->insert($arrayComponente, $arrayCond);  		
		
		return $retorno;
	}

	public function componenteEmPedido($CodigoComponente) {
		$pdo = Conexao::getInstance();

		$crud = bd::getInstance($pdo,$this->nom_tabela);

		$sql = "SELECT COUNT(Cd_Orcamento) as total FROM `COMPONENTES_ITEM_ORCAMENTO` WHERE CD_COMPONENTE = ".$CodigoComponente;

		$dados = $crud->getSQLGeneric($sql);

		return $dados;
	}

	public function excluir($handle) {
		$pdo = Conexao::getInstance();
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		$crud->delete(array('IDCOMPONENTE' => $handle));
	}	
}
?>
