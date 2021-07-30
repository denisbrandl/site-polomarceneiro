<?php
require_once './src/Model/Cliente.php';
require_once './src/Model/Orcamento.php';
require_once './src/Model/UnidadeMedida.php';
require_once './src/Model/Grupo.php';
require_once './src/Model/Dominio.php';
require_once './src/Controller/CommonController.php';
class ClienteController extends CommonController {

	private $modulos = array();
	private $estados = array();
	private $classe = 'Cliente';
	private $breadcrumb = array();
	private $titulo_principal = '';
	
	public function __construct() {
		$cliente = new Cliente();
		$cliente->common = new CommonController();	
		$modulos = $cliente->common->getModulos();
		$estados = $cliente->common->getEstados();
		
		$this->modulos = $modulos;		
		$this->estados = $estados;
		
		$modulo_posicao = array_search($this->classe,array_column($modulos,'modulo'));		
		$this->titulo_principal = $modulos[$modulo_posicao];		
		$this->breadcrumb = array('Cornice'=>URL.'dashboard/index/',$this->titulo_principal['descricao'] => URL.$this->classe.'/listar/');		
	}
	
	public function listar() {

		if (isset($_SESSION['mensagem']) && !empty($_SESSION['mensagem'])) {
			$msg_sucesso = $_SESSION['mensagem'];

			if (isset($_SESSION['tipoMensagem']) && !empty($_SESSION['tipoMensagem'])) {
				$tipo_mensagem = $_SESSION['tipoMensagem'];
			}

			unset($_SESSION['mensagem']);
			unset($_SESSION['tipoMensagem']);
		}								
		$modulos = $this->modulos;
		$classe = $this->classe;
		
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;
		
		require './src/View/Cliente/cliente_listar.php';
	}
	
	public function editar($handle) {
		$msg_sucesso = '';
		$metodo = 'editar';
		$cliente = new Cliente();		
		if (isset($_POST) && !empty($_POST)) {
			$retorno = $cliente->editarCliente($_POST);
			if ($retorno) {
				$msg_sucesso = $this->classe.' alterado com sucesso.';
			}
		}
	
		$UnidadeMedida = new UnidadeMedida();	
		$unidades = $UnidadeMedida->listarTodos();
		
		$grupo = new Grupo();	
		$grupos = $grupo->listarTodos();	
		
		$dominio = new Dominio();
		$dominios = $dominio->listarTodos('TIPO_CLIENTE');
	
		$clientes = $cliente->listarCliente($handle);
		$modulos = $this->modulos;		
		$estados = $this->estados;
		$classe = $this->classe;
		
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		
		require './src/View/Cliente/cliente_form.php';
	}
	
	public function cadastrar() {
		$msg_sucesso = '';	
		$clientes = '';
		$metodo = 'cadastrar';	
		
		$cliente = new Cliente();

		if (isset($_POST) && !empty($_POST)) {
			$retorno = $cliente->cadastrarCliente($_POST);
			if ($retorno) {
				$msg_sucesso = 'Cliente cadastrado com sucesso.';
			}
			$clientes = $cliente->listarCliente($retorno);
		} else {
			$clientes = array($cliente);
		}		
				
		$UnidadeMedida = new UnidadeMedida();	
		$unidades = $UnidadeMedida->listarTodos();
		
		$grupo = new Grupo();	
		$grupos = $grupo->listarTodos();			
		
		
		$modulos = $this->modulos;
		$classe = $this->classe;
		
		$titulo_principal = $this->titulo_principal;
		$breadcrumb = $this->breadcrumb;		
		
		require './src/View/Cliente/cliente_form.php';	
	}
	
	public function carrega() {
		$cliente = new Cliente();
		$clientes = array();
		$arrClientes = array();
		
		if (!$_POST) {
			return json_encode(array());
		}
		
		$clientes = $cliente->listarTodos(1,0,'RazaoSocial',$_POST['q']);
		
		
		foreach ($clientes as $key => $cliente) {
			$arrClientes['itens'][] = array('id' => $cliente->CodigoCliente, 'text' =>$cliente->RazaoSocial);
		}
		
		$clientes['total_count'] = count($clientes);
		
		$arrClientes = json_encode($arrClientes);
		
		echo $arrClientes;
	}

	public function excluir($handle) {
		$msg_sucesso = '';
		$produtos = '';
		$metodo = 'cadastrar';

		$cliente = new Cliente();
		$orcamento = new Orcamento();
		$consultaCliente = $orcamento->pedidosPorCliente($handle);

		$_SESSION['mensagem'] = 'Erro ao excluir o cliente.';
		$_SESSION['tipoMensagem'] = 'callout-danger';
		if ($consultaCliente[0]->total == 0) {
			$cliente->excluir($handle);
			$_SESSION['tipoMensagem'] = 'callout-success';
			$_SESSION['mensagem'] = 'Cliente excluído com sucesso.';
		}
		Header('Location: '.URL.'Cliente/listar/');
		exit();
	}
	
	public function listaClientesDataTables() {
        $cliente = new Cliente();        
        
        $draw = $this->validateGet('draw') ?: 1;
        $length = $this->validateGet('length') ?: 10;
        $start = $this->validateGet('start') ?: 1;
        $searchValue = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';
        $order = '';
        if (isset($_GET['order']) && is_array($_GET['order'])) {
            $order = $_GET['columns'][$_GET['order'][0]['column']]['data'].' '.strtoupper($_GET['order'][0]['dir']);
        }

        $clientes = $cliente->listarTodos(1,($start-1),'RazaoSocial',$searchValue,$length, $order);
        
        $num_registros = $cliente->listarTodosTotal('RazaoSocial',$searchValue);

        $arrClientes = array();
        foreach ($clientes as $cliente) {
        
            $editar = '<a class="btn btn-app" href="'.URL.$this->classe.'/editar/'.$cliente->CodigoCliente.'"><i class="fa fa-edit"></i>Editar</a>';
            $excluir = '<a class="btn btn-app excluirCliente" clienteId="'.$cliente->CodigoCliente.'"  href="#"><i class="fa fa-trash"></i>Excluir</a>';
            if ($cliente->qtdUso > 0) {
                $excluir = '<a class="btn btn-app naoExcluirCliente" style="opacity: 0.4;"><i class="fa fa-trash"></i>Excluir</a>';
            }
            $arrClientes[] = array(
                                    'RazaoSocial' => $cliente->RazaoSocial,
                                    'Nomefantasia' => $cliente->Nomefantasia,
                                    'Endereco' => $cliente->Endereco,
                                    'Telefone1' => $cliente->Telefone1,
                                    'Telefone2' => $cliente->Telefone2,
                                    'Ramal' => $cliente->Ramal,
                                    'EMail' => $cliente->EMail,
                                    'Contato' => $cliente->Contato,
                                    'Editar' => $editar,
                                    'Excluir' => $excluir
            );
        }
        $arrRetorno = array(
            'draw' => $draw,
            'recordsTotal' => $num_registros,
            'recordsFiltered' => $num_registros,
            'data' => $arrClientes
        );
        
        echo json_encode($arrRetorno);
        return;
	}
}
?>
