<?php
require_once './src/Model/Vendedor.php';
require_once './src/Model/UnidadeMedida.php';
require_once './src/Model/Grupo.php';
require_once './src/Model/Dominio.php';
require_once './src/Controller/CommonController.php';
class VendedorController extends CommonController {

	private $modulos = array();
	private $estados = array();
	private $classe = 'Vendedor';
	
	public function __construct() {
		$vendedor = new Vendedor();
		$vendedor->common = new CommonController();	
		$modulos = $vendedor->common->getModulos();
		$estados = $vendedor->common->getEstados();
		
		$this->modulos = $modulos;		
		$this->estados = $estados;
		
		$modulo_posicao = array_search($this->classe,array_column($modulos,'modulo'));
		$this->titulo_principal = $modulos[$modulo_posicao];		
		$this->breadcrumb = array('Cornice'=>URL.'dashboard/index/',$this->titulo_principal['descricao'] => URL.$this->classe.'/listar/');				
	}
	
	public function listar() {
		$vendedor = new Vendedor();
		$vendedor->common = new CommonController();
		
		$coluna = '';
		$buscar = '';
		$pagina_atual = 1;		
		if ($this->validateGet('parametros')) {
			$re = "/^[a-z]+=/"; 
			preg_match($re, $this->validateGet('parametros'), $matches);
			$acao = str_replace('=','',$matches[0]);
			
			$re = "/=([a-zA-Z].*)\|([A-Za-z0-9].*)$/";
			preg_match($re, $this->validateGet('parametros'), $matches);
			
			switch ($acao) {
				case 'buscar':
					if (isset($matches[1])) {
						$coluna = str_replace('=','',$matches[1]);
					}
					if (isset($matches[2])) {
						$buscar = str_replace('=','',$matches[2]);
					}					
				break;
				
				case 'listar':
					$pagina_atual = str_replace('=','',$matches[2]);
			}
		}
		
		$linha_inicial = ($pagina_atual -1) * QTDE_REGISTROS;
		
		$num_registros = $vendedor->listarTodosTotal();
		
		/* Idêntifica a primeira página */  
		$primeira_pagina = 1;   
		
		/* Cálcula qual será a última página */  
		$ultima_pagina  = ceil($num_registros / QTDE_REGISTROS);   
		
		/* Cálcula qual será a página anterior em relação a página atual em exibição */   
		$pagina_anterior = ($pagina_atual > 1) ? $pagina_atual -1 : 0 ;   
		
		/* Cálcula qual será a pŕoxima página em relação a página atual em exibição */   
		$proxima_pagina = ($pagina_atual < $ultima_pagina) ? $pagina_atual +1 : 0 ;  
		
		/* Cálcula qual será a página inicial do nosso range */    
		$range_inicial  = (($pagina_atual - RANGE_PAGINAS) >= 1) ? $pagina_atual - RANGE_PAGINAS : 1 ;   
		
		/* Cálcula qual será a página final do nosso range */    
		$range_final   = (($pagina_atual + RANGE_PAGINAS) <= $ultima_pagina ) ? $pagina_atual + RANGE_PAGINAS : $ultima_pagina ;   
		
		/* Verifica se vai exibir o botão "Primeiro" e "Pŕoximo" */   
		$exibir_botao_inicio = ($range_inicial < $pagina_atual) ? 'mostrar' : 'esconder'; 
		
		/* Verifica se vai exibir o botão "Anterior" e "Último" */   
		$exibir_botao_final = ($range_final > $pagina_atual) ? 'mostrar' : 'esconder';  						
		
		$arrCamposBusca = array('NomeVendedor'	=> 'Nome do Vendedor');		
		
		$vendedores = $vendedor->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);
		
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		$modulos = $this->modulos;
		$classe = $this->classe;
		require './src/View/Vendedor/vendedor_listar.php';
	}
	
	public function editar($handle) {
		$msg_sucesso = '';
		$metodo = 'editar';
		$vendedor = new Vendedor();		
		if (isset($_POST) && !empty($_POST)) {
			$retorno = $vendedor->editarVendedor($_POST);
			if ($retorno) {
				$msg_sucesso = $this->classe.' alterado com sucesso.';
			}
		}
	
		$UnidadeMedida = new UnidadeMedida();	
		$unidades = $UnidadeMedida->listarTodos();
		
		$grupo = new Grupo();	
		$grupos = $grupo->listarTodos();	
		
		$dominio = new Dominio();
		$dominios = $dominio->listarTodos('TIPO_CLIENTE');
		
		$vendedores = new Vendedor();
		$vendedores = $vendedor->listarVendedor($handle);
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		$modulos = $this->modulos;
		$classe = $this->classe;
		require './src/View/Vendedor/vendedor_form.php';
	}
	
	public function cadastrar() {
		$msg_sucesso = '';	
		$vendedores = '';
		$metodo = 'cadastrar';	
		
		$vendedor = new Vendedor();

		if (isset($_POST) && !empty($_POST)) {
			$retorno = $vendedor->cadastrarVendedor($_POST);
			if ($retorno) {
				$msg_sucesso = 'Vendedor cadastrado com sucesso.';
			}
			$vendedores = $vendedor->listarVendedor($retorno);
		} else {
			$vendedores = array($vendedor);
		}		
				
		$UnidadeMedida = new UnidadeMedida();	
		$unidades = $UnidadeMedida->listarTodos();
		
		$grupo = new Grupo();	
		$grupos = $grupo->listarTodos();			
		
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		$modulos = $this->modulos;
		$classe = $this->classe;
		require './src/View/Vendedor/vendedor_form.php';	
	}
}
?>
