<?php
require_once './src/Model/Produto.php';
require_once './src/Model/UnidadeMedida.php';
require_once './src/Model/Grupo.php';
require_once './src/Model/Fornecedor.php';
require_once './src/Controller/CommonController.php';
class ProdutoController extends CommonController {

	private $modulos = array();
	private $estados = array();
	private $classe = 'Produto';
	private $breadcrumb = array();
	private $titulo_principal = '';

	public function __construct() {
		$produto = new Produto();
		$produto->common = new CommonController();
		$modulos = $produto->common->getModulos();
		$estados = $produto->common->getEstados();

		$this->modulos = $modulos;
		$this->estados = $estados;

		$modulo_posicao = array_search($this->classe,array_column($modulos,'modulo'));
		$this->titulo_principal = $modulos[$modulo_posicao];
		$this->breadcrumb = array('Cornice'=>URL.'dashboard/index/',$this->titulo_principal['descricao'] => URL.$this->classe.'/listar/');
	}

	public function listar() {
		$produto = new Produto();
		$produto->common = new CommonController();

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

		$num_registros = $produto->listarTodosTotal();

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

		$arrCamposBusca = array('NovoCodigo'	=> 'Código do Produto',
								'DescricaoProduto'		=> 'Descrição',
								'CodigoProdutoFabricante'			=> 'Código Produto/Fabricante');

		$produtos = $produto->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar,'');

		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;
		$modulos = $this->modulos;
		$classe = $this->classe;
		$msg_sucesso = '';
		$tipo_mensagem = 'callout-success';
		if (isset($_SESSION['mensagem']) && !empty($_SESSION['mensagem'])) {
				$msg_sucesso = $_SESSION['mensagem'];

				if (isset($_SESSION['tipoMensagem']) && !empty($_SESSION['tipoMensagem'])) {
					$tipo_mensagem = $_SESSION['tipoMensagem'];
				}

				unset($_SESSION['mensagem']);
				unset($_SESSION['tipoMensagem']);
		}

		require './src/View/Produto/produto_listar.php';
	}

	public function editar($handle) {
		$msg_sucesso = '';
		$metodo = $acao = 'editar';
		$produto = new Produto();
		$fornecedor = new Fornecedor();
		if (isset($_POST) && !empty($_POST)) {
			$retorno = $produto->editarProduto($_POST);
			if ($retorno) {
				$msg_sucesso = $this->classe.' alterado com sucesso.';
			}
		}

		$UnidadeMedida = new UnidadeMedida();
		$unidades = $UnidadeMedida->listarTodos();

		$grupo = new Grupo();
		$grupos = $grupo->listarTodos();

		$produtos = $produto->listarProduto($handle);

		$consultaProdutoEmPedido = $produto->produtoEmPedido($handle);
		$podeExcluir = false;

		if ($consultaProdutoEmPedido[0]->total == 0) {
			$podeExcluir = true;
		}

		$historicoCompra = $produto->listarHistoricoCompra($handle);

		$fornecedores = $fornecedor->listarTodos();

		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;
		$modulos = $this->modulos;
		$classe = $this->classe;
		$acao = "editar";
		require './src/View/Produto/produto_form.php';
	}

	public function excluir($handle) {
		$msg_sucesso = '';
		$produtos = '';
		$metodo = 'cadastrar';

		$produto = new Produto();
		$consultaProdutoEmPedido = $produto->produtoEmPedido($handle);

		$_SESSION['mensagem'] = 'Erro ao excluir o material.';
		$_SESSION['tipoMensagem'] = 'callout-danger';
		if ($consultaProdutoEmPedido[0]->total == 0) {
			$produto->excluirProduto($handle);
			$_SESSION['tipoMensagem'] = 'callout-success';
			$_SESSION['mensagem'] = 'Material excluído com sucesso.';
		}
		Header('Location: '.URL.'Produto/listar/');
		exit();
	}

	public function cadastrar()
	{
		$msg_sucesso = '';
		$produtos = '';
		$metodo = 'cadastrar';

		$produto = new Produto();
		$fornecedor = new Fornecedor();

		$UnidadeMedida = new UnidadeMedida();
		$unidades = $UnidadeMedida->listarTodos();

		$fornecedores = $fornecedor->listarTodos();

		$historicoCompra = $produto->listarHistoricoCompra(0);

		$grupo = new Grupo();
		$grupos = $grupo->listarTodos();
		$produtos = $produto->listarProduto(0);

		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;
		$modulos = $this->modulos;
		$classe = $this->classe;
		if (isset($_POST) && !empty($_POST)) {
			$retorno = $produto->cadastrarProduto($_POST);
			if ($retorno) {
				$msg_sucesso = 'Produto cadastrado com sucesso.';
				$handle = $retorno;
				$acao = "editar";
				$produtos = $produto->listarProduto($handle);
				$historicoCompra = $produto->listarHistoricoCompra($handle);
				require './src/View/Produto/produto_form.php';
			}
		}

		$acao = "cadastrar";
		require './src/View/Produto/produto_form.php';
	}
}
?>
