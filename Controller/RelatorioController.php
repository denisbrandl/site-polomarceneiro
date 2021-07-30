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

use Dompdf\Dompdf;

class RelatorioController extends CommonController {

	private $modulos = array();
	private $estados = array();
	private $classe = 'Relatorio';
	private $breadcrumb = array();
	private $titulo_principal = '';	
	
	public function __construct() {
		// $orcamento->common = new CommonController();	
		$modulos = $this->getModulos();
		$estados = $this->getEstados();
		
		$this->modulos = $modulos;		
		$this->estados = $estados;
		
		$modulo_posicao = 0; // $modulo_posicao = array_search($this->classe,array_column($modulos,'modulo'));
		
		$this->titulo_principal = array('descricao' => 'Relatório','icone'=>'');			
		$this->breadcrumb = array('Cornice'=>URL.'dashboard/index/',$this->titulo_principal['descricao'] => URL.$this->classe.'/listar/');		
	}
	
	public function pedidosEntregues() {

		$modulos = $this->modulos;
		$classe = $this->classe;
		$orcamento = new Orcamento();
		$situacao = new Situacao();
		$situacoes = $situacao->listarTodos();
		$arrFiltro = [];
		
		$titulo_principal = array('descricao' => 'Relatório - Pedidos Entregue','icone'=>'');
		$breadcrumb = $this->breadcrumb;
		
		$pedidosEntregues = array();
		$periodo_inicial = date('Y-m-d 00:00:00',strtotime('-30 days'));
		$periodo_final 	 =  date('Y-m-d 23:59:59');			
		if ($this->validateGet('parametros')) {
			$re = "/^[a-z]+=/"; 
			preg_match($re, $this->validateGet('parametros'), $matches);
			$acao = str_replace('=','',$matches[0]);
			
			$re = "/([0-9].*)\|([0-9].*)$/";
			preg_match($re, urldecode($this->validateGet('parametros')), $matches);
						
			switch ($acao) {				
				case 'buscar':
					if (isset($matches[0])) {
						$filtros = explode("|",$matches[0]);
						if (isset($filtros[0])) {
							$arrFiltro['periodo_inicial'] = date('Y-m-d 00:00:00',strtotime(str_replace('/','-',$filtros[0])));
						}
						if (isset($filtros[1])) {
							$arrFiltro['periodo_final'] = date('Y-m-d 00:00:00',strtotime(str_replace('/','-',$filtros[1])));;
						}

						if (isset($filtros[2])) {
							$arrFiltro['filtro_situacao'] = $filtros[2] ;
						}
						
						if (isset($filtros[3])) {
							$arrFiltro['filtro_pago'] = $filtros[3];
						}
						
						if (isset($filtros[4])) {
							$arrFiltro['tipo_data'] = $filtros[4];
						}						
						
					}						
										
					$pedidosEntregues = $orcamento->listarOrcamentoPorDataEntregue($arrFiltro);
					
				break;
			}
		}
		
		require './src/View/Relatorio/pedidos_entregue.php';
	}
	
	public function impressaoFabrica() {

		$modulos = $this->modulos;
		$classe = $this->classe;
		$orcamento = new Orcamento();
		$ItemOrcamento = new ItemOrcamento();
		$MolduraItemOrcamento = new Moldura_Item_Orcamento();
		$ComponenteItemOrcamento = new Componente_Item_Orcamento();		
		
		$titulo_principal = array('descricao' => 'Relatório - Pedidos Fábrica','icone'=>'');
		$breadcrumb = $this->breadcrumb;
		
		$periodo_inicial = date('d/m/Y',strtotime('-7 days'));
		$periodo_final = date('d/m/Y');
		
		$pedidosEntregues = array();
				
		if ($this->validateGet('parametros')) {
			$re = "/^[a-z]+=/"; 
			preg_match($re, $this->validateGet('parametros'), $matches);
			$acao = str_replace('=','',$matches[0]);

			$re = "/([0-9].*)\|([0-9].*)$/";
			preg_match($re, urldecode($this->validateGet('parametros')), $matches);
			
			switch ($acao) {				
				case 'buscar':
					if (isset($matches[1])) {
						$periodo_inicial = $matches[1];
					}
					if (isset($matches[2])) {
						$periodo_final = $matches[2];
					}
					
					$periodo_inicial = date('Y-m-d 00:00:00',strtotime(str_replace('/','-',$periodo_inicial)));
					$periodo_final 	 =  date('Y-m-d 23:59:59',strtotime(str_replace('/','-',$periodo_final)));
					
					$pedidosEntregues = $orcamento->listarOrcamentoPorDataPrevista($periodo_inicial,$periodo_final);
					$arrMoldurasItemOrcamento = array();
					$arrComponentesItemOrcamento = array();
					foreach ($pedidosEntregues as $item_orcamento) {
						$handle = $item_orcamento->Cd_Orcamento;
						$moldurasItemOrcamento = $MolduraItemOrcamento->listarMolduraItemOrcamento($item_orcamento->Cd_Item_Orcamento,$handle);
						foreach ($moldurasItemOrcamento as $moldura_item_orcamento) {
							$arrMoldurasItemOrcamento[$handle][$item_orcamento->Cd_Item_Orcamento][] = array('Cd_Produto'=>$moldura_item_orcamento->CodigoProduto,'DescricaoProduto'=>$moldura_item_orcamento->NovoCodigo);
						}
						

						$componentesItemOrcamento = $ComponenteItemOrcamento->listarComponenteItemOrcamento($item_orcamento->Cd_Item_Orcamento,$handle);
						foreach ($componentesItemOrcamento as $componente_item_orcamento) {
							$arrComponentesItemOrcamento[$handle][$item_orcamento->Cd_Item_Orcamento][] = array('Id_Componente'=>$componente_item_orcamento->IDCOMPONENTE,'Descricao'=>$componente_item_orcamento->DESCRICAO);
						}					
						
					}
					
					$periodo_inicial = date('d/m/Y',strtotime($periodo_inicial));
					$periodo_final = date('d/m/Y',strtotime($periodo_final));
					
				break;
			}
		}
		
		require './src/View/Relatorio/impressao_fabrica.php';
	}	

	public function impressaoFabricaPdf() {
				
		$orcamento = new Orcamento();
		$MolduraItemOrcamento = new Moldura_Item_Orcamento();
		$ComponenteItemOrcamento = new Componente_Item_Orcamento();	

		$periodo_inicial = date('Y-m-d 00:00:00',strtotime(str_replace('/','-',$_POST['periodo_inicial'])));
		$periodo_final 	 =  date('Y-m-d 23:59:59',strtotime(str_replace('/','-',$_POST['periodo_final'])));

		$html_relatorio = '';

		$pedidosEntregues = $orcamento->listarOrcamentoPorDataPrevista($periodo_inicial,$periodo_final);
		$arrMoldurasItemOrcamento = array();
		$arrComponentesItemOrcamento = array();
		$CodigoCliente = 0;
		$CodigoPedido = 0;
		$total = 0;
		$total_bruto_cliente = 0;
		$total_desconto_cliente = 0;
		$total_liquido_cliente = 0;
		
		$total_bruto_geral = 0;
		$total_desconto_geral = 0;
		$total_liquido_geral = 0;
		$aux = 1;
		$quantidade = count($pedidosEntregues);

		foreach ($pedidosEntregues as $item_orcamento) {
			if ($CodigoPedido != $item_orcamento->Cd_Orcamento) {

				if ($aux > 1) {
					$html_relatorio .= '</tbody>
											</table></div>'.chr(13).chr(10);
					$html_relatorio .= '<hr><br><div style="page-break-inside: avoid"> </div>'.chr(13).chr(10);
				}
			}
			
			if ($CodigoCliente == 0 || $CodigoCliente != $item_orcamento->CodigoCliente) {
				$nome_cliente = 'Cliente: '.$item_orcamento->RazaoSocial;
			}

			if ($CodigoCliente != $item_orcamento->CodigoCliente) {
				$numero_pedido = sprintf('%s','Pedido: #'.$item_orcamento->Cd_Orcamento);
			}

			if ($CodigoPedido != $item_orcamento->Cd_Orcamento) {

				$data_prevista = sprintf('%s','Data Prevista: '.date('d/m/Y', strtotime($item_orcamento->Dt_Prevista_Entrega)));

				$html_relatorio .= '<div style="page-break-inside: avoid">
				<table>
				<thead>
					<tr role="row">
					<td width="90%">'.$nome_cliente.'<br>'
					.$numero_pedido.'<br>'
					.$data_prevista.'</td>
					</tr>
				</thead>
				</table>
				<table>
					<thead>
					<tr role="row">
						<th width="30" style="width:30px;text-align:center;" align="center">Item</th>
						<th width="100" style="width:100px;text-align:left;">Produto</th>
						<th width="30" style="width:30px;text-align:center;">Alt</th>
						<th width="30" style="width:30px;text-align:center;">Lg</th>
						<th width="30" style="width:30px;text-align:center;">Qtd</th>
						<th width="150" style="width:350px;text-align:left;">Molduras</th>
					</tr>
					</thead>
				</table>
				<table>
				<tbody>';		
			}
			

			$html_relatorio .= '<tr>';

			$html_relatorio .= sprintf('<td width="30" style="width:30px;text-align:center;">%s</td>' , $item_orcamento->Cd_Item_Orcamento).chr(13);			
			$html_relatorio .= sprintf('<td width="100" style="width:100px;text-align:left;">%s</td>', $item_orcamento->Cd_Prod_Aux).chr(13);
			$html_relatorio .= sprintf('<td width="30" style="width:30px;text-align:center;">%s</td>' , $item_orcamento->Md_Altura).chr(13);
			$html_relatorio .= sprintf('<td width="30" style="width:30px;text-align:center;">%s</td>' , $item_orcamento->Md_Largura).chr(13);
			$html_relatorio .= sprintf('<td width="30" style="width:30px;text-align:center;">%s</td>', $item_orcamento->Qt_Item).chr(13);
			
			$html_relatorio .= '<td width="150" style="width:350px;text-align:left;">';

			$handle = $item_orcamento->Cd_Orcamento;
			$moldurasItemOrcamento = $MolduraItemOrcamento->listarMolduraItemOrcamento($item_orcamento->Cd_Item_Orcamento,$handle);
			// if (is_array($moldurasItemOrcamento) && sizeof($arrMoldurasItemOrcamento) > 0) {
				foreach ($moldurasItemOrcamento as $moldura_item_orcamento) {
					$html_relatorio .= $moldura_item_orcamento->NovoCodigo.', ';
				}
			// }
			

			$componentesItemOrcamento = $ComponenteItemOrcamento->listarComponenteItemOrcamento($item_orcamento->Cd_Item_Orcamento,$handle);
			// if (is_array($componentesItemOrcamento) && sizeof($componentesItemOrcamento) > 0) {
				foreach ($componentesItemOrcamento as $componente_item_orcamento) {
					$html_relatorio .= $componente_item_orcamento->DESCRICAO.', ';
				}
			// }
			
			$html_relatorio .= '</td> </tr>';		

			$total_bruto_cliente += (float) $item_orcamento->Vl_Bruto;
			$total_desconto_cliente += $item_orcamento->Vl_Desconto;
			$total_liquido_cliente += $item_orcamento->Vl_Bruto - $item_orcamento->Vl_Desconto;
			
			$CodigoCliente = $item_orcamento->CodigoCliente;
			$CodigoPedido = $item_orcamento->Cd_Orcamento;
			
			if ($aux == $quantidade) {
				$total_bruto_geral += $total_bruto_cliente;
				$total_desconto_geral += $total_desconto_cliente;
				$total_liquido_geral += $total_liquido_cliente - $total_desconto_cliente;
				
				$total_bruto_cliente = 0;
				$total_desconto_cliente = 0;
				$total_liquido_cliente = 0;

				$html_relatorio .= '</tbody>
					</table>';

				$html_relatorio .= '<hr><br><div style="page-break-inside: avoid;">&nbsp;</div>';					
			}
			
			$aux++;			

			
		}
		
		$html_relatorio = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
							<html>
								<head>
									<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">									
								</head>
							<body>
						  '.$html_relatorio.'
							</body>
						  </html>';
						  
		file_put_contents('relatorio.html',$html_relatorio);
		// echo $html_relatorio;exit;

		// instantiate and use the dompdf class
		$dompdf = new Dompdf();
		$dompdf->loadHtml($html_relatorio);

		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4', 'portrait');

		// Render the HTML as PDF
		$dompdf->render();

		$output = $dompdf->output();
		$nome_arquivo = 'relatorio-'.time().'.pdf';
		file_put_contents('./pdfTemporario/' . $nome_arquivo, $output);
		echo json_encode(
			array(
				'success' => true,
				'arquivo' => $nome_arquivo,
				'msg' => ''
				)
		);
		return true;
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
		$produtos = $produto->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar,'NovoCodigo ASC');		
		
		$produtosAuxiliar = $ProdutoAuxiliar->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);		
		
		$componentes = $componente->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);
		
		$clientes = $cliente->listarTodos($pagina_atual,$linha_inicial,'CodigoCliente',$orcamentos[0]->Cd_Cliente);		
		$consumidores = $cliente->listarTodos($pagina_atual,$linha_inicial,'CodigoCliente',(int) $orcamentos[0]->Consumidor_Temp);
		
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
		$produtos = $produto->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar,'NovoCodigo ASC');		
		
		$produtosAuxiliar = $ProdutoAuxiliar->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);		
		
		$componentes = $componente->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);
		
		//$clientes = $cliente->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);
		$vendedores = $vendedor->listarTodos($pagina_atual,$linha_inicial,$coluna,$buscar);
		
		$modulos = $this->modulos;
		$classe = $this->classe;
		
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		
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
		
		$total_bruto = (float) $Vl_Adicionais;
		
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
		
		$total_bruto += $valor_unitario_componentes + $valor_unitario_molduras;
		
		$total_bruto = number_format($total_bruto,"2",".","");
		
		$arrRetorno = array('total'=>$total_bruto);
		
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
}
?>
