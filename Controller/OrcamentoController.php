<?php
require_once './src/Controller/CommonController.php';
require_once './src/Model/Orcamento.php';
require_once './src/Model/ItemOrcamento.php';
require_once './src/Model/Moldura_Item_Orcamento.php';
require_once './src/Model/Componente_Item_Orcamento.php';
require_once './src/Model/Produto.php';
require_once './src/Model/ProdutoAuxiliar.php';
require_once './src/Model/Componente.php';
require_once './src/Model/Cliente.php';
require_once './src/Model/Vendedor.php';
require_once './src/Model/ComposicaoPreco.php';
require_once './src/Model/Situacao.php';
class OrcamentoController extends CommonController {

	private $modulos = array();
	private $estados = array();
	private $classe = 'Orcamento';
	private $breadcrumb = array();
	private $titulo_principal = '';	
	
	public function __construct() {
		$orcamento = new Orcamento();
		$orcamento->common = new CommonController();	
		$modulos = $this->getModulos();
		$estados = $this->getEstados();
		
		$this->modulos = $modulos;		
		$this->estados = $estados;
		
		$modulo_posicao = 0; // $modulo_posicao = array_search($this->classe,array_column($modulos,'modulo'));
		
		$this->titulo_principal = array('descricao' => 'Pedidos','icone'=>'');			
		$this->breadcrumb = array('Cornice'=>URL.'dashboard/index/',$this->titulo_principal['descricao'] => URL.$this->classe.'/listar/');		
	}
	
	public function listar() {
		$orcamento = new Orcamento();
		$situacao = new Situacao();
		$orcamento->common = new CommonController();		
		
		$coluna = '';
		$buscar = '';
		$pagina_atual = 1;
		$arrFiltro = [];		
		if ($this->validateGet('parametros')) {
			$re = "/^[a-z]+=/";

			preg_match($re, $this->validateGet('parametros'), $matches);
			$acao = str_replace('=','',$matches[0]);
			
			// $re = "/=([a-zA-Z].*)\|([A-Za-z0-9].*)$/";
			$re = "/([A-Za-z0-9_]+)=([0-9]+)/m";
			preg_match_all($re, urldecode($this->validateGet('parametros')), $matches);
			switch ($acao) {
				case 'buscar':
					if (isset($matches[0][0])) {
						$arrFiltro[] = $matches[0][0];	
					}
					if (isset($matches[0][1])) {
						$arrFiltro[] = $matches[0][1];	
					}		
					
					if (isset($matches[0][2])) {
						$arrFiltro[] = $matches[0][2];	
					}					
				break;
				
				case 'listar':
					$pagina_atual = str_replace('=','',$matches[2]);
			}
		}
		
		$linha_inicial = ($pagina_atual -1) * QTDE_REGISTROS;
		
		$num_registros = $orcamento->listarTodosTotal();
		
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
		
		$arrCamposBusca = array('Cd_Orcamento'	=> 'Código do Orçamento',
								'RazaoSocial'		=> 'Razão Social');
								
		$orcamentos = $orcamento->listarTodos($pagina_atual,$linha_inicial,0,"Cd_Orcamento DESC",$coluna,$buscar,$arrFiltro);		
		
		$modulos = $this->modulos;
		$classe = $this->classe;
		
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;

		$situacoes = $situacao->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);
		
		require './src/View/Orcamento/orcamento_listar.php';
	}
	
	public function editar($handle) {
		$msg_sucesso = '';
		$metodo = 'editar';
		$modulos = $this->modulos;
		$classe = $this->classe;		
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;
		
		$orcamento = new Orcamento();	
		$produto = new Produto();
		$componente = new Componente();
		$ProdutoAuxiliar = new ProdutoAuxiliar();
		$ItemOrcamento = new ItemOrcamento();
		$MolduraItemOrcamento = new Moldura_Item_Orcamento();
		$ComponenteItemOrcamento = new Componente_Item_Orcamento();
		$cliente = new Cliente();
		$vendedor = new Vendedor();
		$situacao = new Situacao();
		
		if (isset($_POST) && !empty($_POST)) {
			$retorno = $orcamento->cadastrarOrcamento($_POST);			
			if ($retorno) {
				$link_impressao = URL.$classe."/imprimir/".$handle;
				$msg_sucesso = $this->classe.' alterado com sucesso.';
				$msg_sucesso .= '<br><a href="'.$link_impressao.'" style="text-decoration:none;" target="_blank"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Imprimir</a> | <i class="fa fa-envelope-o" aria-hidden="true"></i>&nbsp;Enviar por Email ';				
			}
		}
		
		$orcamentos = $orcamento->listarOrcamento($handle);
		
		$itemsOrcamento = $ItemOrcamento->listarItemOrcamento($handle);

		$arrMoldurasItemOrcamento = array();
		$arrComponentesItemOrcamento = array();
		foreach ($itemsOrcamento as $item_orcamento) {
			$moldurasItemOrcamento = $MolduraItemOrcamento->listarMolduraItemOrcamento($item_orcamento->Cd_Item_Orcamento,$handle);
			foreach ($moldurasItemOrcamento as $moldura_item_orcamento) {
				$arrMoldurasItemOrcamento[$item_orcamento->Cd_Item_Orcamento][] = array('Cd_Produto'=>$moldura_item_orcamento->CodigoProduto,'DescricaoProduto'=>$moldura_item_orcamento->NovoCodigo);
			}
			

			$componentesItemOrcamento = $ComponenteItemOrcamento->listarComponenteItemOrcamento($item_orcamento->Cd_Item_Orcamento,$handle);
			foreach ($componentesItemOrcamento as $componente_item_orcamento) {
				$arrComponentesItemOrcamento[$item_orcamento->Cd_Item_Orcamento][] = array('Id_Componente'=>$componente_item_orcamento->IDCOMPONENTE,'Descricao'=>$componente_item_orcamento->DESCRICAO);
			}					
			
		}		
		
		$coluna = '';
		$buscar = '';
		$pagina_atual = 0;
		$linha_inicial = 0;		
		$produtos = $produto->listarTodos($pagina_atual,$linha_inicial,'Situacao','1','NovoCodigo ASC');		
		
		$produtosAuxiliar = $ProdutoAuxiliar->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);		
		
		$componentes = $componente->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);
		
		// $clientes = $cliente->listarTodos($pagina_atual,$linha_inicial,'CodigoCliente',$orcamentos[0]->Cd_Cliente);		
		// $consumidores = $cliente->listarTodos($pagina_atual,$linha_inicial,'CodigoCliente',(int) $orcamentos[0]->Consumidor_Temp);
		
		$vendedores = $vendedor->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);
		
		$situacoes = $situacao->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);
		
		
		require './src/View/Orcamento/orcamento_form.php';
	}
	
	public function cadastrar() {
		$msg_sucesso = '';	
		$orcamentos = '';
		$metodo = 'cadastrar';	
		
		$orcamento = new Orcamento();
		$produto = new Produto();
		$componente = new Componente();
		$ProdutoAuxiliar = new ProdutoAuxiliar();
		$ItemOrcamento = new ItemOrcamento();
		$MolduraItemOrcamento = new Moldura_Item_Orcamento();
		$ComponenteItemOrcamento = new Componente_Item_Orcamento();
		$cliente = new Cliente();
		$vendedor = new Vendedor();
		$situacao = new Situacao();

		if (isset($_POST) && !empty($_POST)) {
			$retorno = $orcamento->cadastrarOrcamento($_POST);
			if ($retorno) {
				$msg_sucesso = 'Orcamento '.$retorno.' cadastrado com sucesso.';
				$msg_sucesso .= '<br>Imprimir | Enviar por Email '; 
				$orcamentos = $orcamento->listarOrcamento($retorno);				
				
				$itemsOrcamento = $ItemOrcamento->listarItemOrcamento($retorno);

				$arrMoldurasItemOrcamento = array();
				$arrComponentesItemOrcamento = array();
				foreach ($itemsOrcamento as $item_orcamento) {
					$moldurasItemOrcamento = $MolduraItemOrcamento->listarMolduraItemOrcamento($item_orcamento->Cd_Item_Orcamento,$retorno);
					foreach ($moldurasItemOrcamento as $moldura_item_orcamento) {
						$arrMoldurasItemOrcamento[$item_orcamento->Cd_Item_Orcamento][] = array('Cd_Produto'=>$moldura_item_orcamento->CodigoProduto,'DescricaoProduto'=>$moldura_item_orcamento->NovoCodigo);
					}
					

					$componentesItemOrcamento = $ComponenteItemOrcamento->listarComponenteItemOrcamento($item_orcamento->Cd_Item_Orcamento,$retorno);
					foreach ($componentesItemOrcamento as $componente_item_orcamento) {
						$arrComponentesItemOrcamento[$item_orcamento->Cd_Item_Orcamento][] = array('Id_Componente'=>$componente_item_orcamento->IDCOMPONENTE,'Descricao'=>$componente_item_orcamento->DESCRICAO);
					}					
					
				}				
				
			}
		} else {
			$orcamentos = array($orcamento);
		}
		
		$coluna = '';
		$buscar = '';
		$pagina_atual = 0;
		$linha_inicial = 0;		
		$produtos = $produto->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar,'NovoCodigo ASC', array('situacao' => array(1)));		
		
		$produtosAuxiliar = $ProdutoAuxiliar->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);		
		
		$componentes = $componente->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);
		
		// $clientes = $cliente->listarTodos($pagina_atual,$linha_inicial,'CodigoCliente',$orcamentos[0]->Cd_Cliente);		
		// $vendedores = $vendedor->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);				
		
		$modulos = $this->modulos;
		$classe = $this->classe;
		
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		
		$situacoes = $situacao->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);
		
		require './src/View/Orcamento/orcamento_form.php';	
	}
	
	public function calculaTotal() {
		$componente = new Componente();
		$ComposicaoPreco = new ComposicaoPreco();
		$produto = new Produto();
		
		$composicoesPreco = $ComposicaoPreco->listarComposicaoPreco(1);
		$valor_perda = $composicoesPreco[0]->VL_PERDA_MEDIA;
		$valor_margem_bruta = $composicoesPreco[0]->VL_MARGEM_BRUTA;
		
		if (!$_POST) {
			return json_encode(array());
		}
		
		foreach ($_POST as $key => $value) {
			${$key} = $value;
		}
		$total_bruto = 0;

		$Vl_Adicionais = str_replace(".","",$Vl_Adicionais);
		$Vl_Adicionais = str_replace(",",".",$Vl_Adicionais);
	
		$Vl_Moldura = str_replace(".","",$Vl_Moldura);
		$Vl_Moldura = str_replace(",",".",$Vl_Moldura);
		$arrComponentes = explode(',',$componentes);
		$arrMolduras = explode(',',$molduras);
		
		$metro_linear = ($Md_Altura / 100) * ($Md_Largura/100);
		
		$metro_linear = $metro_linear + (($metro_linear * $valor_perda) / 100);
		
		$valor_unitario_componentes = 0;
		
		foreach ($arrComponentes as $componente_value) {
			$componentes = $componente->listarComponente($componente_value);
			$custo_componente = $componentes[0]->CUSTO;
			
			$valor_unitario_componentes += $metro_linear * ($custo_componente + (($custo_componente * $valor_margem_bruta) /100));			

		}
		
		$valor_unitario_molduras = 0;
		$valor_quebra_moldura_anterior = 0;
		foreach ($arrMolduras as $moldura_value) {
			$molduras = $produto->listarProduto($moldura_value);
			$molduras = $molduras[0];
			$custo_moldura = $molduras->PrecoVendaMaoObra;
			
			$Md_Altura_Moldura = ($Md_Altura + $valor_quebra_moldura_anterior) * 2;
			$Md_Largura_Moldura = ($Md_Largura + $valor_quebra_moldura_anterior) * 2;
			
			
			$md_quebra = (int) $molduras->Desenho;
			
			$valor_quebra_moldura_anterior = $valor_quebra_moldura_anterior + $md_quebra;
			
			$Md_Altura_Moldura	= $Md_Altura_Moldura + ($md_quebra * 2);
			$Md_Largura_Moldura	= $Md_Largura_Moldura + ($md_quebra * 2);
			$Md_Total = $Md_Altura_Moldura + $Md_Largura_Moldura;
			$Md_Total = $Md_Total + (($Md_Total * $valor_perda) / 100);
			$valor_unitario_molduras += ($Md_Total * $custo_moldura) / 100;
		}
		
		$total_unitario = ($valor_unitario_componentes + $valor_unitario_molduras) + (float) $Vl_Adicionais + (float) $Vl_Moldura;
		
		$total_bruto =  $total_unitario * $Qt_Item;
		
// 		$total_unitario = number_format($total_unitario,"2",".","");
// 		$total_bruto 	= number_format($total_bruto,"2",".","");
		
		$arrRetorno = array('total_unitario'=> $total_unitario, 'total_bruto' => $total_bruto);
		
		echo json_encode($arrRetorno);
		
	}
	
	public function consultaItensOrcamento() {
		$json = array();
		$orcamento = new Orcamento();
		$ItemOrcamento = new ItemOrcamento();
		$MolduraItemOrcamento = new Moldura_Item_Orcamento();
		$ComponenteItemOrcamento = new Componente_Item_Orcamento();
		
		if (!$_POST) {
			return json_encode(array());
		}		
		foreach ($_POST as $key => $value) {
			${$key} = $value;
		}
		$itemsOrcamento = $ItemOrcamento->listarItemOrcamento($Cd_Orcamento,$item_orcamento);
		$moldurasItemOrcamento = $MolduraItemOrcamento->listarMolduraItemOrcamento($item_orcamento,$Cd_Orcamento);
		foreach ($moldurasItemOrcamento as $moldura_item_orcamento) {
			$json['Molduras'][] = array('Cd_Produto'=>$moldura_item_orcamento->CodigoProduto,'DescricaoProduto'=>$moldura_item_orcamento->NovoCodigo);
		}
		
		foreach ($itemsOrcamento[0] as $key => $value) {
			$json[$key] = $value;
		}
		
		
		echo json_encode($json);
	}
	
	public function imprimir($handle) {
		$orcamento = new Orcamento();	
		$produto = new Produto();
		$componente = new Componente();
		$ProdutoAuxiliar = new ProdutoAuxiliar();
		$ItemOrcamento = new ItemOrcamento();
		$MolduraItemOrcamento = new Moldura_Item_Orcamento();
		$ComponenteItemOrcamento = new Componente_Item_Orcamento();
		$cliente = new Cliente();
		$vendedor = new Vendedor();
		
		$orcamentos = $orcamento->listarOrcamento($handle);
		
		$itemsOrcamento = $ItemOrcamento->listarItemOrcamento($handle);

		$arrMoldurasItemOrcamento = array();
		$arrComponentesItemOrcamento = array();
		foreach ($itemsOrcamento as $item_orcamento) {
			$moldurasItemOrcamento = $MolduraItemOrcamento->listarMolduraItemOrcamento($item_orcamento->Cd_Item_Orcamento,$handle);
			foreach ($moldurasItemOrcamento as $moldura_item_orcamento) {
				$arrMoldurasItemOrcamento[$item_orcamento->Cd_Item_Orcamento][] = array('Cd_Produto'=>$moldura_item_orcamento->CodigoProduto,'DescricaoProduto'=>$moldura_item_orcamento->NovoCodigo);
			}
			

			$componentesItemOrcamento = $ComponenteItemOrcamento->listarComponenteItemOrcamento($item_orcamento->Cd_Item_Orcamento,$handle);
			foreach ($componentesItemOrcamento as $componente_item_orcamento) {
				$arrComponentesItemOrcamento[$item_orcamento->Cd_Item_Orcamento][] = array('Id_Componente'=>$componente_item_orcamento->IDCOMPONENTE,'Descricao'=>$componente_item_orcamento->DESCRICAO);
			}					
			
		}		
		
		$coluna = '';
		$buscar = '';
		$pagina_atual = 0;
		$linha_inicial = 0;		
		$produtos = $produto->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar,'NovoCodigo ASC');		
		
		$produtosAuxiliar = $ProdutoAuxiliar->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);		
		
		$componentes = $componente->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);
		
		$clientes = $cliente->listarTodos($pagina_atual,$linha_inicial,'CodigoCliente',$orcamentos[0]->Cd_Cliente);
		$consumidores = $cliente->listarTodos($pagina_atual,$linha_inicial,'CodigoCliente',(int) $orcamentos[0]->Consumidor_Temp);
		
		$vendedores = $vendedor->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);
		
		$modulos = $this->modulos;
		$classe = $this->classe;
		
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		
		require './src/View/Orcamento/orcamento_pedido_imprimir.php';	
		
	}
	
	public function imprimirOs($handle) {
		$orcamento = new Orcamento();	
		$produto = new Produto();
		$componente = new Componente();
		$ProdutoAuxiliar = new ProdutoAuxiliar();
		$ItemOrcamento = new ItemOrcamento();
		$MolduraItemOrcamento = new Moldura_Item_Orcamento();
		$ComponenteItemOrcamento = new Componente_Item_Orcamento();
		$cliente = new Cliente();
		$vendedor = new Vendedor();
		
		$orcamentos = $orcamento->listarOrcamento($handle);
		
		$itemsOrcamento = $ItemOrcamento->listarItemOrcamento($handle);

		$arrMoldurasItemOrcamento = array();
		$arrComponentesItemOrcamento = array();
		foreach ($itemsOrcamento as $item_orcamento) {
			$moldurasItemOrcamento = $MolduraItemOrcamento->listarMolduraItemOrcamento($item_orcamento->Cd_Item_Orcamento,$handle);
			foreach ($moldurasItemOrcamento as $moldura_item_orcamento) {
				$arrMoldurasItemOrcamento[$item_orcamento->Cd_Item_Orcamento][] = array('Cd_Produto'=>$moldura_item_orcamento->CodigoProduto,'DescricaoProduto'=>$moldura_item_orcamento->NovoCodigo);
			}
			

			$componentesItemOrcamento = $ComponenteItemOrcamento->listarComponenteItemOrcamento($item_orcamento->Cd_Item_Orcamento,$handle);
			foreach ($componentesItemOrcamento as $componente_item_orcamento) {
				$arrComponentesItemOrcamento[$item_orcamento->Cd_Item_Orcamento][] = array('Id_Componente'=>$componente_item_orcamento->IDCOMPONENTE,'Descricao'=>$componente_item_orcamento->DESCRICAO);
			}					
			
		}		
		
		$coluna = '';
		$buscar = '';
		$pagina_atual = 0;
		$linha_inicial = 0;		
		$produtos = $produto->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar,'NovoCodigo ASC');		
		
		$produtosAuxiliar = $ProdutoAuxiliar->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);		
		
		$componentes = $componente->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);
		
		$clientes = $cliente->listarTodos($pagina_atual,$linha_inicial,'CodigoCliente',$orcamentos[0]->Cd_Cliente);
		$consumidores = $cliente->listarTodos($pagina_atual,$linha_inicial,'CodigoCliente',(int) $orcamentos[0]->Consumidor_Temp);
		
		$vendedores = $vendedor->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);
		
		$modulos = $this->modulos;
		$classe = $this->classe;
		
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		
		require './src/View/Orcamento/orcamento_pedido_imprimir_os.php';	
		
	}
	
	public function cadastrarCliente() {
		$cliente = new Cliente();
		if (!$_POST) {
				echo json_encode(array('success' => 0));
				return false;
		}
		$retorno = $cliente->cadastrarCliente($_POST);
		if ($retorno) {
				echo json_encode(array('success' => 1,'message' => "Cliente cadastrado com sucesso"));
				return true;
		}

	}

}
?>
