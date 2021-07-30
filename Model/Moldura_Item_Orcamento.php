<?php
require_once('conexao.php');
require_once('bd.php');
class Moldura_Item_Orcamento {

	public $Cd_Orcamento;
	public $Cd_Item_Orcamento;
	public $Sequencia;
	public $Cd_Produto;
	public $Qt_Item;
	public $Vl_Unitario;
	public $Id_Situacao_Item;
	public $AlterarValor;
	private $nom_tabela = 'Moldura_Item_Orcamento';
	private $order_by_default = 'Sequencia ASC, Cd_Item_Orcamento ASC';		
	
	public function __construct() {
		$Cd_Orcamento = "";
		$Cd_Item_Orcamento = "";
		$Sequencia = "";
		$Cd_Produto = "";
		$Qt_Item = "";
		$Vl_Unitario = "";
		$Id_Situacao_Item = "";
		$AlterarValor = "";
	}
	
	public function listarMolduraItemOrcamento($Cd_Item_Orcamento,$Cd_Orcamento) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
// 		$sql = "SELECT * FROM ".$this->nom_tabela." WHERE Cd_Item_Orcamento = ? AND Cd_Orcamento = ?";
		
		$sql = "SELECT 
					p.CodigoProduto, p.DescricaoProduto, p.CodigoProdutoFabricante, NovoCodigo
				FROM Moldura_Item_Orcamento mio
				INNER JOIN Produtos p ON (mio.Cd_Produto = p.CodigoProduto)
				WHERE mio.Cd_Item_Orcamento = ? 
				AND mio.Cd_Orcamento = ? ORDER BY Sequencia ASC";
				
		
		$arrayParam = array($Cd_Item_Orcamento,$Cd_Orcamento); 
		
		$dados = $crud->getSQLGeneric($sql,$arrayParam, TRUE);
		
		return $dados;
		
		//
	}
}
?>

