<?php
require_once './src/Model/ComposicaoPreco.php';
require_once './src/Model/Grupo.php';
require_once './src/Model/Dominio.php';
require_once './src/Controller/CommonController.php';
class ComposicaoPrecoController extends CommonController {

	private $modulos = array();
	private $estados = array();
	private $classe = 'ComposicaoPreco';
	
	public function __construct() {
		$composicaoPreco = new ComposicaoPreco();
		$composicaoPreco->common = new CommonController();	
		$modulos = $composicaoPreco->common->getModulos();
		$estados = $composicaoPreco->common->getEstados();
		
		$this->modulos = $modulos;		
		$this->estados = $estados;
		
		$modulo_posicao = array_search($this->classe,array_column($modulos,'modulo'));
		$this->titulo_principal = $modulos[$modulo_posicao];		
		$this->breadcrumb = array('Cornice'=>URL.'dashboard/index/',$this->titulo_principal['descricao'] => URL.$this->classe.'/listar/');
		
	}
	
	public function listar() {
		$composicaoPreco = new ComposicaoPreco();
		
		$pagina_atual = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
		$linha_inicial = ($pagina_atual -1) * QTDE_REGISTROS;
		
		$num_registros = $composicaoPreco->listarTodosTotal();
		
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
		
		
		$composicoesPreco = $composicaoPreco->listarTodos($pagina_atual,$linha_inicial);
		
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		$modulos = $this->modulos;
		$classe = $this->classe;	
		require './src/View/ComposicaoPreco/composicao_preco_listar.php';
	}
	
	public function editar($handle = 0) {
		$msg_sucesso = '';
		$metodo = 'editar';
		$composicaoPreco = new ComposicaoPreco();		
		if (isset($_POST) && !empty($_POST)) {
			$retorno = $composicaoPreco->editarComposicaoPreco($_POST);
			if ($retorno) {
				$msg_sucesso = $this->classe.' alterado com sucesso.';
			}
		}
		
		$composicoesPreco = new ComposicaoPreco();
		$composicoesPreco = $composicaoPreco->listarComposicaoPreco($handle);

		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		$modulos = $this->modulos;
		$classe = $this->classe;			
		
		require './src/View/ComposicaoPreco/composicao_preco_form.php';
	}
	
	public function cadastrar() {
		$msg_sucesso = '';	
		$composicoesPreco = '';
		$metodo = 'cadastrar';	
		
		$composicaoPreco = new ComposicaoPreco();

		if (isset($_POST) && !empty($_POST)) {
			$retorno = $composicaoPreco->cadastrarComposicaoPreco($_POST);
			if ($retorno) {
				$msg_sucesso = 'ComposicaoPreco cadastrado com sucesso.';
			}
			$composicoesPreco = $composicaoPreco->listarComposicaoPreco($retorno);
		} else {
			$composicoesPreco = array($composicaoPreco);
		}
		
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		$modulos = $this->modulos;
		$classe = $this->classe;	
		require './src/View/ComposicaoPreco/composicao_preco_form.php';	
	}
}
?>
