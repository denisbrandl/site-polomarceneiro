<?php
require_once('conexao.php');
require_once('bd.php');
class Componente_Item_Orcamento {

	public $cd_orcamento;
	public $item_orcamento;
	public $sequencia;
	public $cd_componente;
	public $valor_unitario;
	public $alterar_valor;
	private $nom_tabela = 'COMPONENTES_ITEM_ORCAMENTO';
	private $order_by_default = 'Sequencia ASC, Cd_Item_Orcamento ASC';		
	
	public function __construct() {
		$cd_orcamento = "";
		$item_orcamento = "";
		$sequencia = "";
		$cd_componente = "";
		$valor_unitario = "";
		$alterar_valor = "";
	}
	
	public function listarComponenteItemOrcamento($Cd_Item_Orcamento,$Cd_Orcamento) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "SELECT 
					c.IDCOMPONENTE, c.DESCRICAO
				FROM 
					COMPONENTES_ITEM_ORCAMENTO cio
					INNER JOIN COMPONENTES c ON (cio.CD_COMPONENTE = c.IDCOMPONENTE)
				WHERE 
					cio.ITEM_ORCAMENTO = ? 
					AND cio.CD_ORCAMENTO = ?";
					
		$arrayParam = array($Cd_Item_Orcamento,$Cd_Orcamento); 
		
		$dados = $crud->getSQLGeneric($sql,$arrayParam, TRUE);
		
		return $dados;
	}
}
?>

