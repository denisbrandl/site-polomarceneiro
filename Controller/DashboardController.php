<?php
require_once './src/Model/Dashboard.php';
require_once './src/Controller/CommonController.php';
require_once './src/Model/Orcamento.php';
class DashboardController extends CommonController {

	private $modulos = array();
	private $estados = array();
	private $classe = 'Dashboard';
	
	public function __construct() {
		$dashboard = new Dashboard();
		$dashboard->common = new CommonController();	
		$modulos = $dashboard->common->getModulos();		
		$this->modulos = $modulos;
	}
	
	public function index() {
		$modulos = $this->modulos;
		$classe = $this->classe;
		
		$ultimosPedidos = $this->ultimosPedidos();  
		$pedidosPorSituacao = $this->pedidosPorSituacao();	
		
		$titulo_principal = array('descricao' => 'Dashboard','icone'=> '');
		$breadcrumb = array('Cornice'=>URL.'dashboard/index/');
		
		require './src/View/Dashboard/dashboard_index.php';
	}
	
	public function ultimosPedidos() {
		$orcamento = new Orcamento();
		$orcamentos = $orcamento->listarTodos(0,0,10,'Cd_Orcamento DESC');
		
		return $orcamentos;
	}
	
	private function pedidosPorSituacao() {
		$orcamento = new Orcamento();
		$orcamentos = $orcamento->pedidosPorSituacao();
		$arrPedidosPorSituacao = array();
		
		foreach ($orcamentos as $orcamento) {
			$arrPedidosPorSituacao[$orcamento->idSituacao] = $orcamento->total;
		}
		return $arrPedidosPorSituacao;	
	}
}
?>
