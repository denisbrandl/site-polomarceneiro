<?php
require_once('conexao.php');
require_once('bd.php');
class ItemOrcamento {
	public $Cd_Orcamento;
	public $Cd_Item_Orcamento;
	public $Cd_Prod_Aux;
	public $Qt_Item;
	public $Md_Altura;
	public $Md_Largura;
	public $Vl_Unitario;
	public $Vl_Bruto;
	public $VL_desconto;
	public $Id_Situacao;
	public $Ds_Observacao;
	public $ID_VD_1_CAMADA;
	public $ID_VD_2_CAMADA;
	public $ID_FDO_EUCATEX;
	public $ID_ESPELHO;
	public $VL_VD_1_CAMADA;
	public $VL_VD_2_CAMADA;
	public $VL_FDO_EUCATEX;
	public $VL_ESPELHO;
	public $AlterarValor;
	public $VL_ADICIONAIS;	
	private $nom_tabela = 'Item_Orcamento';
	private $order_by_default = 'Cd_Item_Orcamento ASC';		
	
	public function __construct() {
		$Cd_Orcamento = "";
		$Cd_Item_Orcamento = "";
		$Cd_Prod_Aux = "";
		$Qt_Item = "";
		$Md_Altura = "";
		$Md_Largura = "";
		$Vl_Unitario = "";
		$Vl_Bruto = "";
		$VL_desconto = "";
		$Id_Situacao = "";
		$Ds_Observacao = "";
		$ID_VD_1_CAMADA = "";
		$ID_VD_2_CAMADA = "";
		$ID_FDO_EUCATEX = "";
		$ID_ESPELHO = "";
		$VL_VD_1_CAMADA = "";
		$VL_VD_2_CAMADA = "";
		$VL_FDO_EUCATEX = "";
		$VL_ESPELHO = "";
		$AlterarValor = "";
		$VL_ADICIONAIS = "";	
	}
	
	public function listarItemOrcamento($handle,$Cd_Item_Orcamento = 0) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		if ($Cd_Item_Orcamento > 0) {
			$sql = "SELECT * FROM ".$this->nom_tabela." WHERE Cd_Orcamento = ? AND Cd_Item_Orcamento = ?";
			$arrayParam = array($handle,$Cd_Item_Orcamento); 
		} else {
			$sql = "SELECT * FROM ".$this->nom_tabela." WHERE Cd_Orcamento = ?";		
			$arrayParam = array($handle); 
		}
		
		$dados = $crud->getSQLGeneric($sql,$arrayParam, TRUE);
		
		return $dados;
		
		//
	}		
}