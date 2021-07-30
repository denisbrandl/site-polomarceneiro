<?php
require_once './src/Model/Componente.php';
require_once './src/Controller/CommonController.php';
class ComponenteController extends CommonController {
	private $modulos = array();
	private $estados = array();
	private $classe = 'Componente';	
	private $breadcrumb = array();
	private $titulo_principal = '';
	
	public function __construct() {
		$componente = new Componente();
		$componente->common = new CommonController();	
		$modulos = $componente->common->getModulos();
		$estados = $componente->common->getEstados();
		
				
		$this->modulos = $modulos;		
		$this->estados = $estados;
		
		$modulo_posicao = array_search($this->classe,array_column($modulos,'modulo'));
		$this->titulo_principal = $modulos[$modulo_posicao];		
		$this->breadcrumb = array('Cornice'=>URL.'dashboard/index/',$this->titulo_principal['descricao'] => URL.$this->classe.'/listar/');
	}	
	
	public function listar() {		
		$componente = new Componente();
		$componente->common = new CommonController();
		
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
		
		$num_registros = $componente->listarTodosTotal();
		
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
		
		$arrCamposBusca = array('DESCRICAO'	=> 'Descrição',
								'CUSTO'  => 'Custo');		
		
		$componentes = $componente->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);

		$msg_sucesso = '';
		$tipo_mensagem = '';
		if (isset($_SESSION['mensagem']) && !empty($_SESSION['mensagem'])) {
			$msg_sucesso = $_SESSION['mensagem'];

			if (isset($_SESSION['tipoMensagem']) && !empty($_SESSION['tipoMensagem'])) {
				$tipo_mensagem = $_SESSION['tipoMensagem'];
			}

			unset($_SESSION['mensagem']);
			unset($_SESSION['tipoMensagem']);
		}

		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		$modulos = $this->modulos;
		$classe = $this->classe;	
		
		require './src/View/Componente/componente_listar.php';
	}
	
	public function editar($handle) {
		$msg_sucesso = '';
		$metodo = 'editar';
		$componente = new Componente();		
		if (isset($_POST) && !empty($_POST)) {
			$retorno = $componente->editarComponente($_POST);
			if ($retorno) {
				$msg_sucesso = 'Componente alterado com sucesso.';
			}
		}
		
		$componentes = $componente->listarComponente($handle);
		
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		$modulos = $this->modulos;
		$classe = $this->classe;	
		require './src/View/Componente/componente_form.php';
	}
	
	public function cadastrar() {
		$msg_sucesso = '';	
		$componentes = '';
		$metodo = 'cadastrar';
		
		$componente = new Componente();		
		if (isset($_POST) && !empty($_POST)) {
			$handle = $componente->cadastrarComponente($_POST);
			if ($retorno) {
				$msg_sucesso = 'Componente cadastrado com sucesso.';
			}
		}		

		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		$modulos = $this->modulos;
		$classe = $this->classe;		
		$componentes = $componente->listarComponente($handle);		
		require './src/View/Componente/componente_form.php';	
	}

	public function excluir($handle) {
		$msg_sucesso = '';
		$produtos = '';
		$metodo = 'cadastrar';

		$componente = new Componente();
		$consultaComponenteEmPedido = $componente->componenteEmPedido($handle);

		$_SESSION['mensagem'] = 'Erro ao excluir o componente.';
		$_SESSION['tipoMensagem'] = 'callout-danger';
		if ($consultaComponenteEmPedido[0]->total == 0) {
			$componente->excluir($handle);
			$_SESSION['tipoMensagem'] = 'callout-success';
			$_SESSION['mensagem'] = 'Componente excluído com sucesso.';
		}
		Header('Location: '.URL.'Componente/listar/');
		exit();
	}	
}
?>
