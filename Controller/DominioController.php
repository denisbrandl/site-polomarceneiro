<?php
require_once './src/Model/Dominio.php';
require_once './src/Model/UnidadeMedida.php';
require_once './src/Model/Grupo.php';
require_once './src/Controller/CommonController.php';
class DominioController extends CommonController {

	private $modulos = array();
	private $classe = 'Dominio';
	
	public function __construct() {
		$dominio = new Dominio();
		$dominio->common = new CommonController();	
		$modulos = $dominio->common->getModulos();
		
		$this->modulos = $modulos;		
	}
	
	public function listar() {
		$dominio = new Dominio();
		
		$ds_dominio = '';
		if (isset($_GET['ds_dominio']) && !empty($_GET['ds_dominio'])) {
			$ds_dominio = $_GET['ds_dominio'];
		}
		
		$dominios = $dominio->listarTodos($ds_dominio);
		
		$modulos = $this->modulos;
		$classe = $this->classe;
		require './src/View/Dominio/cliente_listar.php';
	}
	
	public function editar($handle) {
		$msg_sucesso = '';
		$metodo = 'editar';
		$dominio = new Dominio();		
		if (isset($_POST) && !empty($_POST)) {
			$retorno = $dominio->editarDominio($_POST);
			if ($retorno) {
				$msg_sucesso = $this->classe.' alterado com sucesso.';
			}
		}
	
		$UnidadeMedida = new UnidadeMedida();	
		$unidades = $UnidadeMedida->listarTodos();
		
		$grupo = new Grupo();	
		$grupos = $grupo->listarTodos();	
	
		$dominios = $dominio->listarDominio($handle);
		$modulos = $this->modulos;		
		
		$classe = $this->classe;
		require './src/View/Dominio/cliente_form.php';
	}
	
	public function cadastrar() {
		$msg_sucesso = '';	
		$dominios = '';
		$metodo = 'cadastrar';
		
		$dominio = new Dominio();		
		if (isset($_POST) && !empty($_POST)) {
			$retorno = $dominio->cadastrarDominio($_POST);
			if ($retorno) {
				$msg_sucesso = 'Dominio cadastrado com sucesso.';
			}
		}		

		$UnidadeMedida = new UnidadeMedida();	
		$unidades = $UnidadeMedida->listarTodos();
		
		$grupo = new Grupo();	
		$grupos = $grupo->listarTodos();
		
		$modulos = $this->modulos;		
		$dominios = $dominio->listarDominio($handle);		
		
		$classe = $this->classe;		
		require './src/View/Dominio/cliente_form.php';	
	}
}
?>
