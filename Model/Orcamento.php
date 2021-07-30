<?php
require_once('conexao.php');
require_once('bd.php');
class Orcamento {

	public $Cd_Orcamento;
	public $Dt_Orcamento;
	public $Dt_Prevista_Entrega;
	public $Dt_Efetiva_Entrega;
	public $Cd_Cliente;
	public $Cd_Forma_Pgto;
	public $Cd_Vendedor;
	public $Vl_Bruto;
	public $VL_desconto;
	public $vl_liquido;
	public $Id_Situacao;
	public $Vl_Moldura;
	public $Ds_Observacao_Pedido;
	public $Ds_Observacao_Producao;
	public $Consumidor_Temp;
	public $Producao_Finalizada;
	public $Vl_Entrada;
	public $Pago;
	private $nom_tabela = 'Orcamento';
	private $order_by_default = 'Cd_Orcamento DESC';	

	
	public function __construct() {
		$Cd_Orcamento = '';
		$Dt_Orcamento = '';
		$Dt_Prevista_Entrega = '';
		$Dt_Efetiva_Entrega = '';
		$Cd_Cliente = '';
		$Cd_Forma_Pgto = '';
		$Cd_Vendedor = '';
		$Vl_Bruto = '';
		$VL_desconto = '';
		$vl_liquido = '';
		$Id_Situacao = '';
		$Ds_Observacao_Pedido = '';
		$Ds_Observacao_Producao = '';
		$Consumidor_Temp = '';
		$Producao_Finalizada = '';
		$Vl_Entrada = '';
		$Pago = 0;
	}
	
	public function listarTodos($pagina_atual,$linha_inicial,$limit = 0,$order = "", $coluna = "", $buscar="",$filtro=array()) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$where = '';
		if ($coluna != '' && $buscar != '') {
			$where = sprintf(' WHERE %s LIKE UPPER("%s%%") ',$coluna,strtoupper($buscar));
		}
		
		if (!empty($filtro) && is_array($filtro)) {
			foreach ($filtro as $f_k => $f_v) {
				if ($f_v == 'Id_Situacao=7') {
					$f_v = 'Id_Situacao IN (2,3) and Dt_Prevista_Entrega < NOW()';
				}
				if (!empty($where)) {
					$where = $where . ' AND '.$f_v;
				} else {
					$where = ' WHERE '.$f_v;
				}
			}
		}
		
		$paginacao = '';				
		
		if ($pagina_atual >= 0 && $linha_inicial > 0) {
			$paginacao = " LIMIT {$linha_inicial}, ".QTDE_REGISTROS;
		} elseif ($limit > 0) {
			$paginacao = " LIMIT {$limit} ";
		}
		
		$ordenacao = " ORDER BY ".$this->order_by_default;
		if ($order != "") {
			$ordenacao = " ORDER BY ".$order;
		}
		
		$sql = "SELECT ".$this->nom_tabela.".*, Clientes.RazaoSocial, Clientes.Nomefantasia, Situacao.descricao FROM ".$this->nom_tabela.' LEFT JOIN Clientes ON (Clientes.CodigoCliente = Orcamento.Cd_Cliente) INNER JOIN Situacao ON (Situacao.idSituacao = Orcamento.Id_Situacao) '.$where.$ordenacao.$paginacao;
		$dados = $crud->getSQLGeneric($sql);
		return $dados;
		
		//
	}

	public function listarTodosTotal() {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "SELECT count(Cd_Orcamento) as total_registros FROM ".$this->nom_tabela;		
		
		$dados = $crud->getSQLGeneric($sql,null,FALSE);		
		
		return $dados->total_registros;
		
		//
	}	
	
	public function listarOrcamento($handle) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "SELECT *, Clientes.* FROM ".$this->nom_tabela." LEFT JOIN Clientes ON (Orcamento.Cd_Cliente = Clientes.CodigoCliente) WHERE Cd_Orcamento = ?";

		$arrayParam = array($handle); 
		
		$dados = $crud->getSQLGeneric($sql,$arrayParam, TRUE);
		
		return $dados;
		
		//
	}	
	
	public function listarOrcamentoPorDataEntregue($arrFiltro = array()) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$where = '';
		$condicional = '';
		if (sizeof($arrFiltro)) {
			if ( isset($arrFiltro['periodo_inicial']) && isset($arrFiltro['periodo_final']) ) 
			{
				$tipo_data = 'Orcamento.Dt_Efetiva_Entrega';
				if (isset($arrFiltro['tipo_data'])) {
					$tipo_data = 'Orcamento.' . $arrFiltro['tipo_data'];
				}
				
				$where .= sprintf(" %s %s BETWEEN '%s' AND '%s'", $condicional, $tipo_data, $arrFiltro['periodo_inicial'], $arrFiltro['periodo_final']);
				$condicional = ' AND ';
			}
			
			if (isset($arrFiltro['filtro_situacao']) && !empty($arrFiltro['filtro_situacao'])) {
				$where .= sprintf(" %s Orcamento.Id_Situacao IN (%s)", $condicional, $arrFiltro['filtro_situacao']);
				$condicional = ' AND ';
			}
			
			if (isset($arrFiltro['filtro_pago']) && $arrFiltro['filtro_pago'] != "-1") {
				$where .= sprintf(" %s Orcamento.Pago = %s", $condicional, $arrFiltro['filtro_pago']);
				$condicional = ' AND ';
			}
		}
		
		if (!empty($where)) {
			$where = ' WHERE '.$where;
		}
		
		$sql = "SELECT 
					Clientes.CodigoCliente,
					Clientes.RazaoSocial,
					Orcamento.Cd_Orcamento,
					Orcamento.Dt_Efetiva_Entrega,
					Orcamento.Dt_Prevista_Entrega,
					Orcamento.Vl_Bruto,
					Orcamento.Vl_Desconto,
					Orcamento.Vl_Liquido,
					Item_Orcamento.Cd_Item_Orcamento,
					Item_Orcamento.Cd_Prod_Aux,
					Item_Orcamento.Md_Altura,
					Item_Orcamento.Md_Largura,
					Item_Orcamento.Qt_Item,
					Item_Orcamento.Vl_Unitario,
					Item_Orcamento.Ds_Observacao,			
					Item_Orcamento.Ds_ObservacaoProducao
				FROM
					Orcamento
					LEFT JOIN Clientes ON (Orcamento.Cd_Cliente = Clientes.CodigoCliente)
					INNER JOIN Item_Orcamento ON (Orcamento.Cd_Orcamento = Item_Orcamento.Cd_Orcamento)
				 $where 
				ORDER BY
					Orcamento.Cd_Orcamento,
					Item_Orcamento.Cd_Item_Orcamento					
					";
		// echo $sql;exit;
		$dados = $crud->getSQLGeneric($sql,array(), TRUE);
		
		return $dados;
	}		
	
	public function listarOrcamentoPorDataPrevista($periodo_inicial, $periodo_final) {
		$pdo = Conexao::getInstance();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "SELECT 
					Clientes.CodigoCliente,
					Clientes.RazaoSocial,
					Orcamento.Cd_Orcamento,
					Orcamento.Dt_Efetiva_Entrega,
					Orcamento.Dt_Prevista_Entrega,
					Orcamento.Vl_Bruto,
					Orcamento.Vl_Desconto,
					Orcamento.Vl_Liquido,
					Orcamento.Ds_Observacao_Producao,
					Item_Orcamento.Cd_Item_Orcamento,
					Item_Orcamento.Cd_Prod_Aux,
					Item_Orcamento.Md_Altura,
					Item_Orcamento.Md_Largura,
					Item_Orcamento.Qt_Item,
					Item_Orcamento.Vl_Unitario,
					Item_Orcamento.Ds_Observacao,
					Item_Orcamento.Ds_ObservacaoProducao
				FROM
					Orcamento
					LEFT JOIN Clientes ON (Orcamento.Cd_Cliente = Clientes.CodigoCliente)
					INNER JOIN Item_Orcamento ON (Orcamento.Cd_Orcamento = Item_Orcamento.Cd_Orcamento)
				WHERE
					Orcamento.Dt_Prevista_Entrega BETWEEN '$periodo_inicial' AND '$periodo_final'
				ORDER BY
					Orcamento.Dt_Prevista_Entrega ASC,
					Orcamento.Cd_Orcamento ASC,
					Item_Orcamento.Cd_Item_Orcamento					
					";
					
		
		$dados = $crud->getSQLGeneric($sql,array(), TRUE);
		
		return $dados;
	}			
	
	public function editarOrcamento($post) {
		$pdo = Conexao::getInstance();

		$arrayOrcamento = array();
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'Cd_Orcamento')
				$arrayOrcamento[$key] =  $value;
		}
		// print_r($post);exit;
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$arrayCond = array('Cd_Orcamento=' => $post['handle']);  
		$retorno   = $crud->update($arrayOrcamento, $arrayCond);  		
		
		return $retorno;
	}
	
	public function cadastrarOrcamento($post) {	
		$pdo = Conexao::getInstance();
		$Cd_Orcamento = 0;
		$arrayOrcamento = array('Dt_Orcamento' => 0,'Cd_Cliente' => 0,'Vl_Bruto' => 0,'VL_desconto' => 0, 'vl_liquido' => 0,'Vl_Entrada'=> 0,'Id_Situacao' => 0,'Consumidor_Temp' => 0, 'Dt_Prevista_Entrega'=>0, 'Dt_Efetiva_Entrega' => 0, 'Pago' => 0,'Ds_Observacao_Pedido' => '', 'Ds_Observacao_Producao' => '' );
		$arrItemOrcamento = array('Cd_Prod_Aux'=>0,'Qt_Item'=>0,'Md_Altura'=>0,'Md_Largura'=>0,'Vl_Unitario'=>0,'Vl_Total'=>0,'Ds_Observacao' => '', 'Ds_ObservacaoProducao' => '','VL_ADICIONAIS' => 0, 'Vl_Moldura' => 0);
		$arrComponentesOrcamento = array('CD_Componente' => 0);
		$arrMoldurasOrcamento = array('Cd_Produto' => 0);
		// print_r($post);exit;
		foreach ($post as $key => $value) {
			if ($key != 'handle' && $key != 'Cd_Orcamento' && $key != 'Cd_Prod_Aux_Pedido') {			
				if (array_key_exists($key,$arrayOrcamento)) {
					if ( ($key == 'Dt_Orcamento' || $key == 'Dt_Prevista_Entrega' || $key == 'Dt_Efetiva_Entrega') && !empty(trim($value))) {						
							if (preg_match('/^[0-9]+\/[0-9]+\/[0-9]{4}$/m',$value) == 1) {
								$value = date('Y-m-d H:i:s',strtotime(str_replace('/','-',$value)));
							}
					} else if ( ($key == 'Dt_Orcamento' || $key == 'Dt_Prevista_Entrega' || $key == 'Dt_Efetiva_Entrega') && $value == '') {
						$value = NULL;
					}
					
					switch ($key) {
						case 'VL_desconto':
						// case 'vl_liquido':
						case 'Vl_Entrada':
							$value = str_replace(".","",$value);
							$value = str_replace(",",".",$value);
							$value = number_format($value,2,".","");
							break;
					}					
					
					$arrayOrcamento[$key] =  $value;
				}
			}
			if ($key == 'Cd_Orcamento' && (int) $value > 0) {
				$Cd_Orcamento = $value;
			}
		}
		// print_r($arrayOrcamento);
		// exit;
		$arrItemsOrcamento = array();
		$arrItemsMoldurasOrcamento = array();
		$arrItemsComponentesOrcamento = array();
		foreach ($post as $key => $value) {
			if ($value  != "" && array_key_exists($key,$arrItemOrcamento)) {
				if (is_array($post[$key])) {
					foreach ($post[$key] as $key_ => $value_) {						
						if ($key == 'Qt_Item') {
							foreach ($post[$key] as $itens_orcamento => $item_orcamento) {
								if (isset($post['Cd_Produto'][$itens_orcamento]) && !empty($post['Cd_Produto'][$itens_orcamento]) && empty($arrItemsMoldurasOrcamento[$itens_orcamento])) {
									foreach ($post['Cd_Produto'][$itens_orcamento] as $moldura_key) {
										$arrItemsMoldurasOrcamento[$itens_orcamento][] = $moldura_key;
									}
								}
								
								if (isset($post['CD_Componente'][$itens_orcamento]) && !empty($post['CD_Componente'][$itens_orcamento]) && empty($arrItemsComponentesOrcamento[$itens_orcamento])) {
									foreach ($post['CD_Componente'][$itens_orcamento] as $componente_key) {
										$arrItemsComponentesOrcamento[$itens_orcamento][] = $componente_key;
									}
								}								
							}
							$arrItemsOrcamento[$key_][$key] = $value_; 
						} elseif ($key == "Vl_Total") {	
							// $value_ = floatval(str_replace(',', '.', str_replace('.', '', $value_)));
							$arrItemsOrcamento[$key_]["Vl_Bruto"] = $value_;
						} else {
							switch ($key) {
								case 'VL_ADICIONAIS':
								case 'Vl_Moldura':
								case 'VL_desconto':
								case 'vl_liquido':
								case 'Vl_Entrada':
									$value_ = floatval(str_replace(',', '.', str_replace('.', '', $value_)));
									break;
							}
							$arrItemsOrcamento[$key_][$key] = $value_; 
						}
					}
				}
			}
		}
		
		// print_r($arrItemsOrcamento);exit;

		$crud = bd::getInstance($pdo,$this->nom_tabela);
		if ($Cd_Orcamento > 0) {
			$arrayCond = array('Cd_Orcamento=' => $Cd_Orcamento);  
			$crud->update($arrayOrcamento,$arrayCond);
		} else {
			$Cd_Orcamento  = $crud->insert($arrayOrcamento);
		}

		
		$crud_item_orcamento = bd::getInstance($pdo,'Item_Orcamento');		
		$crud_moldura_item_orcamento = bd::getInstance($pdo,'Moldura_Item_Orcamento');
		$crud_componentes_item_orcamento = bd::getInstance($pdo,'COMPONENTES_ITEM_ORCAMENTO');
		
		$crud_item_orcamento->delete(array('Cd_Orcamento' => $Cd_Orcamento));
		$crud_moldura_item_orcamento->delete(array('CD_ORCAMENTO' => $Cd_Orcamento));
		$crud_componentes_item_orcamento->delete(array('CD_ORCAMENTO' => $Cd_Orcamento));
		
		foreach ($arrItemsOrcamento as $item_orcamento_key => $item_orcamento_value) {	
			$arrTemp = array();
			foreach ($item_orcamento_value as $key => $value) {
				$arrTemp[$key] = $value;
			}
			$arrTemp['Cd_Orcamento'] = $Cd_Orcamento;
			$Cd_Item_Orcamento  = $crud_item_orcamento->insert($arrTemp);	

			if (isset($arrItemsMoldurasOrcamento[$item_orcamento_key])) {
				$seqMoldura = 1;
				foreach ($arrItemsMoldurasOrcamento[$item_orcamento_key] as $item_moldura_orcamento_key => $item_moldura_orcamento_value) {
						$arrTemp = array();
						$arrTemp['Cd_Produto'] = $item_moldura_orcamento_value;
						$arrTemp['Cd_Orcamento'] = $Cd_Orcamento;
						$arrTemp['Cd_Item_Orcamento'] = $Cd_Item_Orcamento;
						$arrTemp['Sequencia'] = $seqMoldura;
						$crud_moldura_item_orcamento->insert($arrTemp);
						$seqMoldura++;
				}
			}
			if (isset($arrItemsComponentesOrcamento[$item_orcamento_key])) {
				$seqComponente = 1;
				foreach ($arrItemsComponentesOrcamento[$item_orcamento_key] as $item_componentes_orcamento_key => $item_componentes_orcamento_value) {
						$arrTemp = array();
						$arrTemp['CD_COMPONENTE'] = $item_componentes_orcamento_value;
						$arrTemp['CD_ORCAMENTO'] = $Cd_Orcamento;
						$arrTemp['ITEM_ORCAMENTO'] = $Cd_Item_Orcamento;
						$arrTemp['SEQUENCIA'] = $item_componentes_orcamento_key;
						$crud_componentes_item_orcamento->insert($arrTemp);
						$seqComponente++;
			}			
			}
		}
		return $Cd_Orcamento;		
	}
	
	function pedidosPorSituacao() {
		$pdo = Conexao::getInstance();
		$arrayParam = array();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "select s.idSituacao, s.descricao, (select count(o.Cd_Orcamento) from ".$this->nom_tabela." o WHERE o.Id_Situacao = s.idSituacao) as total from Situacao s";
		$sql .= ' UNION 
					SELECT "-1", "Atrasado", COUNT(o.Cd_Orcamento) from Orcamento o WHERE o.Id_Situacao IN (2,3) and o.Dt_Prevista_Entrega < NOW()';

		$dados = $crud->getSQLGeneric($sql,$arrayParam, TRUE);
		
		return $dados;
	}
	
	function pedidosPorCliente($handle_cliente) {
		$pdo = Conexao::getInstance();
		$arrayParam = array();
		
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		
		$sql = "select count(o.Cd_Orcamento) as total from Orcamento o WHERE Cd_Cliente = ".$handle_cliente;
		
		$dados = $crud->getSQLGeneric($sql,$arrayParam, TRUE);
		
		return $dados;
	}
}
?>
