<?php
require_once './src/Model/Usuario.php';
class CommonController {
		public function __construct() {
			$usuario = new Usuario();
			$_SESSION['NomeUsuario'] = '';
			if (isset($_SESSION['token']) && !empty($_SESSION['token'])) {
				$dados = $usuario->info($_SESSION['token']);
				$_SESSION['NomeUsuario'] = $dados->NomeUsuario;
			}
		}
		
		public function getModulos() {
			$modulos[] = array('modulo' => 'Banco' 			, 'descricao' => 'Bancos'                  	, 'icone' => 'fa-bank');
			$modulos[] = array('modulo' => 'Cliente' 		, 'descricao' => 'Clientes'					, 'icone' => 'fa-users');
			$modulos[] = array('modulo' => 'Componente' 	, 'descricao' => 'Componentes'             	, 'icone' => 'fa-archive');
			$modulos[] = array('modulo' => 'ComposicaoPreco', 'descricao' => 'Composição de Preço'	    , 'icone' => 'fa-money');
// 			$modulos[] = array('modulo' => 'FormaPagamento' , 'descricao' => 'Formas de Pagamento'  	, 'icone' => 'fa-dollar');
			$modulos[] = array('modulo' => 'Fornecedor' 	, 'descricao' => 'Fornecedores'            	, 'icone' => 'fa-industry');
			$modulos[] = array('modulo' => 'Grupo' 			, 'descricao' => 'Grupos de Produtos'      	, 'icone' => 'fa-cubes');
			$modulos[] = array('modulo' => 'Moeda' 			, 'descricao' => 'Moedas'               	, 'icone' => 'fa-circle-o');
			$modulos[] = array('modulo' => 'Produto'		, 'descricao' => 'Materiais'				, 'icone' => 'fa-th');			
			$modulos[] = array('modulo' => 'ProdutoAuxiliar', 'descricao' => 'Tipos de Imagens'         , 'icone' => 'fa-picture-o');						
			$modulos[] = array('modulo' => 'UnidadeMedida'	, 'descricao' => 'Unidades de Medidas'     	, 'icone' => 'fa-balance-scale');
			$modulos[] = array('modulo' => 'Vendedor' 		, 'descricao' => 'Vendedores'              	, 'icone' => 'fa-exchange');
			$modulos[] = array('modulo' => 'Afiliado' 		, 'descricao' => 'Afiliados'              	, 'icone' => 'fa fa-user-secret');
			$modulos[] = array('modulo' => 'Configuracoes'	, 'descricao' => 'Configurações'          	, 'icone' => 'fa fa-wrench');
			return $modulos;
		}
		
		public function getEstados() {
			$estados = array("AC"=>"Acre", 
							"AL"=>"Alagoas", 
							"AM"=>"Amazonas", 
							"AP"=>"Amapá",
							"BA"=>"Bahia",
							"CE"=>"Ceará",
							"DF"=>"Distrito Federal",
							"ES"=>"Espírito Santo",
							"GO"=>"Goiás",
							"MA"=>"Maranhão",
							"MT"=>"Mato Grosso",
							"MS"=>"Mato Grosso do Sul",
							"MG"=>"Minas Gerais",
							"PA"=>"Pará",
							"PB"=>"Paraíba",
							"PR"=>"Paraná",
							"PE"=>"Pernambuco",
							"PI"=>"Piauí",
							"RJ"=>"Rio de Janeiro",
							"RN"=>"Rio Grande do Norte",
							"RO"=>"Rondônia",
							"RS"=>"Rio Grande do Sul",
							"RR"=>"Roraima",
							"SC"=>"Santa Catarina",
							"SE"=>"Sergipe",
							"SP"=>"São Paulo",
							"TO"=>"Tocantins");
			return $estados;
		}
		
		public function validatePost($post) {
			if (isset($_POST[$post]) && !empty($_POST[$post])) {
				return $_POST[$post];
			} else {
				return false;
			}
		}
		
		public function validateGet($get) {
			if (isset($_GET[$get]) && !empty($_GET[$get])) {
				return $_GET[$get];
			} else {
				return false;
			}
		}		
		
}
?>