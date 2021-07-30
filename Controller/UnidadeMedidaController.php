<?php
require_once './src/Model/UnidadeMedida.php';
require_once './src/Model/Grupo.php';
require_once './src/Model/Dominio.php';
require_once './src/Controller/CommonController.php';
class UnidadeMedidaController extends CommonController {

	private $modulos = array();
	private $estados = array();
	private $classe = 'UnidadeMedida';
	private $breadcrumb = array();
	private $titulo_principal = '';		
	
	public function __construct() {
		$unidadeMedida = new UnidadeMedida();
		$unidadeMedida->common = new CommonController();	
		$modulos = $unidadeMedida->common->getModulos();
		$estados = $unidadeMedida->common->getEstados();
		
		$this->modulos = $modulos;		
		$this->estados = $estados;
		
		$modulo_posicao = array_search($this->classe,array_column($modulos,'modulo'));
		$this->titulo_principal = $modulos[$modulo_posicao];		
		$this->breadcrumb = array('Cornice'=>URL.'dashboard/index/',$this->titulo_principal['descricao'] => URL.$this->classe.'/listar/');				
	}
	
	public function listar() {
		$unidadeMedida = new UnidadeMedida();
		$unidadeMedida->common = new CommonController();
		
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
		
		$num_registros = $unidadeMedida->listarTodosTotal();
		
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
		
		$arrCamposBusca = array('DescricaoUnidadeMedida'	=> 'Código',
								'NomeUnidadeMedida'		=> 'Descrição');	
		
		
		$unidadesMedida = $unidadeMedida->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);
		
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		$modulos = $this->modulos;
		$classe = $this->classe;
		require './src/View/UnidadeMedida/unidade_medida_listar.php';
	}
	
	public function editar($handle) {
		$msg_sucesso = '';
		$metodo = 'editar';
		$unidadeMedida = new UnidadeMedida();		
		if (isset($_POST) && !empty($_POST)) {
			$retorno = $unidadeMedida->editarUnidadeMedida($_POST);
			if ($retorno) {
				$msg_sucesso = $this->classe.' alterado com sucesso.';
			}
		}
		
		$unidadesMedida = new UnidadeMedida();
		$unidadesMedida = $unidadeMedida->listarUnidadeMedida($handle);
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		$modulos = $this->modulos;
		$classe = $this->classe;
		require './src/View/UnidadeMedida/unidade_medida_form.php';
	}
	
	public function cadastrar() {
		$msg_sucesso = '';	
		$unidadesMedida = '';
		$metodo = 'cadastrar';	
		
		$unidadeMedida = new UnidadeMedida();

		if (isset($_POST) && !empty($_POST)) {
			$retorno = $unidadeMedida->cadastrarUnidadeMedida($_POST);
			if ($retorno) {
				$msg_sucesso = 'UnidadeMedida cadastrado com sucesso.';
			}
			$unidadesMedida = $unidadeMedida->listarUnidadeMedida($retorno);
		} else {
			$unidadesMedida = array($unidadeMedida);
		}		
				
		$UnidadeMedida = new UnidadeMedida();	
		$unidades = $UnidadeMedida->listarTodos();
		
		$grupo = new Grupo();	
		$grupos = $grupo->listarTodos();			
		
		
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		$modulos = $this->modulos;
		$classe = $this->classe;
		require './src/View/UnidadeMedida/unidade_medida_form.php';	
	}
}
?>
