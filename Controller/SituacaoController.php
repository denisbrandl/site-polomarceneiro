<?php
require_once './src/Model/Situacao.php';
require_once './src/Controller/CommonController.php';
class SituacaoController extends CommonController {

	private $modulos = array();
	private $estados = array();
	private $classe = 'Situacao';
	private $breadcrumb = array();
	private $titulo_principal = '';
	
	public function __construct() {
		$situacao = new Situacao();
		$situacao->common = new CommonController();	
		$modulos = $situacao->common->getModulos();
		$estados = $situacao->common->getEstados();
		
		$this->modulos = $modulos;		
		$this->estados = $estados;
		
		$modulo_posicao = array_search($this->classe,array_column($modulos,'modulo'));		
		$this->titulo_principal = $modulos[$modulo_posicao];		
		$this->breadcrumb = array('Cornice'=>URL.'dashboard/index/',$this->titulo_principal['descricao'] => URL.$this->classe.'/listar/');		
	}
	
	public function listar() {
		$situacao = new Situacao();
		
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
		
		$num_registros = $situacao->listarTodosTotal();
		
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
		
		
		$situacoes = $situacao->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);
		
		
		$arrCamposBusca = array('idSituacao'	=> 'Código',
								'descricao'  => 'Descrição'
								);
		
		$modulos = $this->modulos;
		$classe = $this->classe;
		
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;
		
		require './src/View/Situacao/situacao_listar.php';
	}
	
	public function editar($handle) {
		$msg_sucesso = '';
		$metodo = 'editar';
		$situacao = new Situacao();		
		if (isset($_POST) && !empty($_POST)) {
			$retorno = $situacao->editarSituacao($_POST);
			if ($retorno) {
				$msg_sucesso = $this->classe.' alterado com sucesso.';
			}
		}
		
		$situacoes = $situacao->listarSituacao($handle);
		$modulos = $this->modulos;		
		$estados = $this->estados;
		$classe = $this->classe;
		
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		
		require './src/View/Situacao/situacao_form.php';
	}
	
	public function cadastrar() {
		$msg_sucesso = '';	
		$situacoes = '';
		$metodo = 'cadastrar';	
		
		$situacao = new Situacao();

		if (isset($_POST) && !empty($_POST)) {
			$retorno = $situacao->cadastrarSituacao($_POST);
			if ($retorno) {
				$msg_sucesso = 'Situação cadastrado com sucesso.';
			}
			$situacoes = $situacao->listarSituacao($retorno);
		} else {
			$situacoes = array($situacao);
		}								
		
		$modulos = $this->modulos;
		$classe = $this->classe;
		
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		
		require './src/View/Situacao/cliente_form.php';	
	}
}
?>
