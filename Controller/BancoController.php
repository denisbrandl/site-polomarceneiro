<?php
require_once './src/Model/Banco.php';
require_once './src/Model/Grupo.php';
require_once './src/Model/Dominio.php';
require_once './src/Controller/CommonController.php';
class BancoController extends CommonController {

	private $modulos = array();
	private $estados = array();
	private $classe = 'Banco';
	private $breadcrumb = array();
	private $titulo_principal = '';
	
	public function __construct() {
		$banco = new Banco();
		$banco->common = new CommonController();	
		$modulos = $banco->common->getModulos();
		$estados = $banco->common->getEstados();
		
				
		$this->modulos = $modulos;		
		$this->estados = $estados;
		
		$modulo_posicao = array_search($this->classe,array_column($modulos,'modulo'));
		$this->titulo_principal = $modulos[$modulo_posicao];		
		$this->breadcrumb = array('Cornice'=>URL.'dashboard/index/',$this->titulo_principal['descricao'] => URL.$this->classe.'/listar/');
	}
	
	public function listar() {
		$banco = new Banco();

		$coluna = '';
		$buscar = '';
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
			}
		}
		
		$pagina_atual = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;		
		$linha_inicial = ($pagina_atual -1) * QTDE_REGISTROS;
		
		$num_registros = $banco->listarTodosTotal();
		
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
		
		$arrCamposBusca = array('RazaoSocial' => 'Razão Social','Agencia'=>'Agência','ContaCorrente'=>'Conta');
		
		$bancos = $banco->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);
		
		$modulos = $this->modulos;
		$classe = $this->classe;
		
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;
		
		require './src/View/Banco/banco_listar.php';
	}
	
	public function editar($handle) {
		$msg_sucesso = '';
		$metodo = 'editar';
		$banco = new Banco();		
		if (isset($_POST) && !empty($_POST)) {
			$retorno = $banco->editarBanco($_POST);
			if ($retorno) {
				$msg_sucesso = $this->classe.' alterado com sucesso.';
			}
		}
		
		$bancos = new Banco();
		$bancos = $banco->listarBanco($handle);
		$modulos = $this->modulos;		
		$estados = $this->estados;
		$classe = $this->classe;

		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		
		require './src/View/Banco/banco_form.php';
	}
	
	public function cadastrar() {
		$msg_sucesso = '';	
		$bancos = '';
		$metodo = 'cadastrar';	
		
		$banco = new Banco();

		if (isset($_POST) && !empty($_POST)) {
			$retorno = $banco->cadastrarBanco($_POST);
			if ($retorno) {
				$msg_sucesso = 'Banco cadastrado com sucesso.';
			}
			$bancos = $banco->listarBanco($retorno);
		} else {
			$bancos = array($banco);
		}		
						
		$modulos = $this->modulos;
		$classe = $this->classe;
		
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		
		require './src/View/Banco/banco_form.php';	
	}
}
?>