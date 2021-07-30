<?php
require_once './src/Model/UnidadeMedida.php';
require_once './src/Model/Grupo.php';
require_once './src/Model/Dominio.php';
require_once './src/Controller/CommonController.php';
class GrupoController extends CommonController {

	private $modulos = array();
	private $estados = array();
	private $classe = 'Grupo';
	private $breadcrumb = array();
	private $titulo_principal = '';		
	
	public function __construct() {
		$grupo = new Grupo();
		$grupo->common = new CommonController();	
		$modulos = $grupo->common->getModulos();
		$estados = $grupo->common->getEstados();
		
		$this->modulos = $modulos;		
		$this->estados = $estados;
		
		$modulo_posicao = array_search($this->classe,array_column($modulos,'modulo'));
		$this->titulo_principal = $modulos[$modulo_posicao];		
		$this->breadcrumb = array('Cornice'=>URL.'dashboard/index/',$this->titulo_principal['descricao'] => URL.$this->classe.'/listar/');				
	}
	
	public function listar() {
		$grupo = new Grupo();		
		$grupo->common = new CommonController();
		
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
		
		$num_registros = $grupo->listarTodosTotal();
		
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
		
		$arrCamposBusca = array('DescricaoGrupo'	=> 'Descrição'
								);
		
		
		$grupos = $grupo->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);
		
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		$modulos = $this->modulos;
		$classe = $this->classe;	
		require './src/View/Grupo/grupo_listar.php';
	}
	
	public function editar($handle) {
		$msg_sucesso = '';
		$metodo = 'editar';
		$grupo = new Grupo();		
		if (isset($_POST) && !empty($_POST)) {
			$retorno = $grupo->editarGrupo($_POST);
			if ($retorno) {
				$msg_sucesso = $this->classe.' alterado com sucesso.';
			}
		}
	
		$UnidadeMedida = new UnidadeMedida();	
		$unidades = $UnidadeMedida->listarTodos();
		
		$pagina_atual = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
		$linha_inicial = ($pagina_atual -1) * QTDE_REGISTROS;		
		
		$grupo = new Grupo();	
		$grupos = $grupo->listarTodos($pagina_atual,$linha_inicial);	
		
		$dominio = new Dominio();
		$dominios = $dominio->listarTodos('TIPO_CLIENTE');
		
		$grupos = new Grupo();
		$grupos = $grupo->listarGrupo($handle);
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		$modulos = $this->modulos;
		$classe = $this->classe;	
		require './src/View/Grupo/grupo_form.php';
	}
	
	public function cadastrar() {
		$msg_sucesso = '';	
		$grupos = '';
		$metodo = 'cadastrar';	
		
		$grupo = new Grupo();

		if (isset($_POST) && !empty($_POST)) {
			$retorno = $grupo->cadastrarGrupo($_POST);
			if ($retorno) {
				$msg_sucesso = 'Grupo cadastrado com sucesso.';
			}
			$grupos = $grupo->listarGrupo($retorno);
		} else {
			$grupos = array($grupo);
		}		
				
		$UnidadeMedida = new UnidadeMedida();	
		$unidades = $UnidadeMedida->listarTodos();
		
		$grupo = new Grupo();	
		$grupos = $grupo->listarTodos();			
		
		
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		$modulos = $this->modulos;
		$classe = $this->classe;	
		require './src/View/Grupo/grupo_form.php';	
	}
}
?>
