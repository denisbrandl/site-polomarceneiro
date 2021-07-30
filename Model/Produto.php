<?php
require_once('conexao.php');
require_once('bd.php');
class Produto {
	public $CodigoProduto;
	public $DescricaoProduto;
	public $DescricaoTraduzida;
	public $CodigoProdutoFabrica;
	public $UnidadeProduto;
	public $CodigoGrupo;
	public $PrecoCusto;
	public $PrecoVenda;
	public $CodigoFornecedor;
	public $Quantidade;
	public $QuantidadeMinima;
	public $QuantidadeReservada;
	public $DataUltimaCompra;
	public $ValorUltimaCompra;
	public $ClassificacaoFiscal;
	public $SituacaoTributaria;
	public $QuantidadeMaxima;
	public $PrecoMinimo;
	public $QtdadeSaidaMesAtual;
	public $QtdadeSaidaMesPassado;
	public $QtdadeSaidaMesRetrasado;
	public $Moeda;
	public $CodigoIPI;
	public $OrigemProduto;
	public $IndServico;
	public $CodigoGrupoEstoque;
	public $Detalhes;
	public $NovoCodigo;
	public $Desenho;
	public $PrecoVendaMaoObra;
	public $Situacao;
	private $nom_tabela = 'Produtos';
	private $order_by_default = 'DescricaoProduto';

	public function __construct() {
		$CodigoProdutoPrimária= '';
		$DescricaoProduto= '';
		$DescricaoTraduzida= '';
		$CodigoProdutoFabricante= '';
		$UnidadeProduto= '';
		$CodigoGrupo= '';
		$PrecoCusto= '';
		$PrecoVenda= '';
		$CodigoFornecedor= '';
		$Quantidade= '';
		$QuantidadeMinima= '';
		$QuantidadeReservada= '';
		$DataUltimaCompra= '';
		$ValorUltimaCompra= '';
		$ClassificacaoFiscal= '';
		$SituacaoTributaria= '';
		$QuantidadeMaxima= '';
		$PrecoMinimo= '';
		$QtdadeSaidaMesAtual= '';
		$QtdadeSaidaMesPassado= '';
		$QtdadeSaidaMesRetrasado= '';
		$Moeda= '';
		$CodigoIPI= '';
		$OrigemProduto= '';
		$IndServico= '';
		$CodigoGrupoEstoque= '';
		$Detalhes= '';
		$NovoCodigo= '';
		$Desenho= '';
		$PrecoVendaMaoObra= '';
		$Situacao = '';
	}


	public function listarTodos($pagina_atual = 0,$linha_inicial = 0,$coluna = '',$buscar = '',$order = "", $arrayFiltro = array()) {
		$pdo = Conexao::getInstance();

		$crud = bd::getInstance($pdo,$this->nom_tabela);

		$where = '';
		if ($coluna != '' && $buscar != '') {
			$where = sprintf(' WHERE %s LIKE UPPER("%s%%") ',$coluna,strtoupper($buscar));
		}

        if (sizeof($arrayFiltro)) {
            $_and = ' AND ';
            if (empty($where)) {
                $where = ' WHERE ';
                $_and = '';
            }
            if (isset($arrayFiltro['situacao'])) {
                $where .= ' Situacao IN ('.implode(',',$arrayFiltro['situacao']).')';
            }
        }

		$paginacao = '';
		if ($pagina_atual > 0 && $linha_inicial > 0) {
			$paginacao = " LIMIT {$linha_inicial}, ".QTDE_REGISTROS;
		}

		if (empty($order)) {
			$order = $this->order_by_default;
		}

		$sql = "SELECT *, (SELECT COUNT(Cd_Orcamento) FROM `Moldura_Item_Orcamento` WHERE Moldura_Item_Orcamento.Cd_Produto = Produtos.CodigoProduto ) as qtdUso FROM ".$this->nom_tabela.$where." ORDER BY ".$order.$paginacao;
		$dados = $crud->getSQLGeneric($sql);

		return $dados;
	}

	public function listarProduto($handle) {
		$pdo = Conexao::getInstance();

		$crud = bd::getInstance($pdo,$this->nom_tabela);

		$sql = "SELECT * FROM ".$this->nom_tabela." WHERE CodigoProduto = ?";
		$arrayParam = array($handle);

		$dados = $crud->getSQLGeneric($sql,$arrayParam, TRUE);

		return $dados;

		//
	}

	public function listarTodosTotal() {
		$pdo = Conexao::getInstance();

		$crud = bd::getInstance($pdo,$this->nom_tabela);

		$sql = "SELECT count(*) as total_registros FROM ".$this->nom_tabela;

		$dados = $crud->getSQLGeneric($sql,null,FALSE);

		return $dados->total_registros;

		//
	}

	public function listarHistoricoCompra($CodigoProduto = 0,$CodigoFornecedor = 0) {
		$pdo = Conexao::getInstance();

		$crud = bd::getInstance($pdo,$this->nom_tabela);
		if ($CodigoProduto > 0) {
			$sql = "SELECT hcp.historicoId , hcp.CodigoFornecedor , hcp.CodigoProduto , hcp.valorPago , hcp.dataCompra, for.RazaoSocial, pro.NovoCodigo, pro.DescricaoProduto FROM `historicoComprasProdutos` hcp INNER JOIN Fornecedores `for` ON (for.CodigoFornecedor = hcp.CodigoFornecedor) INNER JOIN `Produtos` pro ON (hcp.CodigoProduto = pro.CodigoProduto)  WHERE hcp.CodigoProduto = ".$CodigoProduto." ORDER BY dataCompra DESC";
		} else {
			$sql = "SELECT hcp.historicoId , hcp.CodigoFornecedor , hcp.CodigoProduto , hcp.valorPago , hcp.dataCompra, for.RazaoSocial, pro.NovoCodigo, pro.DescricaoProduto FROM `historicoComprasProdutos` hcp INNER JOIN Fornecedores `for` ON (for.CodigoFornecedor = hcp.CodigoFornecedor) INNER JOIN `Produtos` pro ON (hcp.CodigoProduto = pro.CodigoProduto)  WHERE hcp.CodigoFornecedor = ".$CodigoFornecedor." ORDER BY dataCompra DESC";
		}

		$dados = $crud->getSQLGeneric($sql);
		return $dados;

		//
	}

	public function produtoEmPedido($CodigoProduto) {
			$pdo = Conexao::getInstance();

			$crud = bd::getInstance($pdo,$this->nom_tabela);

			$sql = "SELECT COUNT(Cd_Orcamento) as total FROM `Moldura_Item_Orcamento` WHERE Cd_Produto = ".$CodigoProduto;

			$dados = $crud->getSQLGeneric($sql);

			return $dados;
	}

	public function excluirProduto($handle) {
		$pdo = Conexao::getInstance();
		$crud = bd::getInstance($pdo,$this->nom_tabela);
		$retorno = $crud->delete(array('CodigoProduto' => $handle));
	}

	public function editarProduto($post) {
		$pdo = Conexao::getInstance();
		$crud_historico_compras_produtos = bd::getInstance($pdo,'historicoComprasProdutos');
		$dataCompra = "";

		if (isset($post['excluirHistorico']) && !empty($post['excluirHistorico'])) {
			foreach ($post['excluirHistorico'] as $item_historico_excluir) {

				$crud_historico_compras_produtos->delete(array('historicoId' => $item_historico_excluir));
			}
		}
		unset($post['excluirHistorico']);

		if (isset($post['dataCompra']) && !empty($post['dataCompra'])) {
			$dataCompra = $post['dataCompra'];
			$dataCompra = date("Y-m-d", strtotime(str_replace('/','-',$dataCompra)));
		}
		unset($post['dataCompra']);

		$CodigoFornecedorCompra = "";
		if (isset($post['CodigoFornecedorCompra']) && !empty($post['CodigoFornecedorCompra'])) {
			$CodigoFornecedorCompra = $post['CodigoFornecedorCompra'];
		}
		unset($post['CodigoFornecedorCompra']);

		$valorPago = "";
		if (isset($post['valorPago']) && !empty($post['valorPago'])) {
			$valorPago = $post['valorPago'];
			$valorPago = str_replace(".","",$valorPago);
			$valorPago = str_replace(",",".",$valorPago);
		}
		unset($post['valorPago']);

		$arrayProduto = array();
		foreach ($post as $key => $value) {
			// if ($key != 'handle' && $key != 'NovoCodigo')
			if ($key != 'handle')
				$arrayProduto[$key] =  $value;
		}

		if (!empty($dataCompra) && !empty($CodigoFornecedorCompra) && !empty($valorPago)) {
			$crud = bd::getInstance($pdo,'historicoComprasProdutos');

			$retorno   = $crud->insert(array('CodigoFornecedor' => $CodigoFornecedorCompra,'CodigoProduto' => $post['handle'] ,'valorPago' => $valorPago, 'dataCompra' => $dataCompra));
		}

		$crud = bd::getInstance($pdo,$this->nom_tabela);

		$arrayCond = array('CodigoProduto=' => $post['handle']);
		$retorno   = $crud->update($arrayProduto, $arrayCond);

		return $retorno;
	}

	public function cadastrarProduto($post)
    {
        $pdo = Conexao::getInstance();
        unset($post['excluirHistorico']);
        if (isset($post['dataCompra']) && !empty($post['dataCompra'])) {
            $dataCompra = $post['dataCompra'];
            $dataCompra = date("Y-m-d", strtotime(str_replace('/', '-', $dataCompra)));
        }
        unset($post['dataCompra']);

        $CodigoFornecedorCompra = "";
        if (isset($post['CodigoFornecedorCompra']) && !empty($post['CodigoFornecedorCompra'])) {
            $CodigoFornecedorCompra = $post['CodigoFornecedorCompra'];
        }
        unset($post['CodigoFornecedorCompra']);

        $valorPago = "";
        if (isset($post['valorPago']) && !empty($post['valorPago'])) {
            $valorPago = $post['valorPago'];
            $valorPago = str_replace(".", "", $valorPago);
            $valorPago = str_replace(",", ".", $valorPago);
        }
        unset($post['valorPago']);

        $arrayProduto = array();
        foreach ($post as $key => $value) {
            if ($key != 'handle') {
                if (!empty($value)) {
                    $arrayProduto[$key] =  $value;
                }
            }
        }

        $crud = bd::getInstance($pdo, $this->nom_tabela);
        $retorno   = $crud->insert($arrayProduto);

        if (!empty($dataCompra) && !empty($CodigoFornecedorCompra) && !empty($valorPago)) {
            $crud = bd::getInstance($pdo,'historicoComprasProdutos');
            $crud->insert(array('CodigoFornecedor' => $CodigoFornecedorCompra,'CodigoProduto' => $retorno ,'valorPago' => $valorPago, 'dataCompra' => $dataCompra));
        }

        return $retorno;
    }
}
?>
