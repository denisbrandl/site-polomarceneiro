<?php
session_start();

require 'vendor/autoload.php';

use SuaMadeira\UsuarioController;
use SuaMadeira\MarcaController;
use SuaMadeira\LinhaController;
use SuaMadeira\CorController;
use SuaMadeira\CategoriaController;
use SuaMadeira\MaterialController;
use SuaMadeira\MunicipioController;
use SuaMadeira\EstadoController;
use SuaMadeira\EmailController;
use SuaMadeira\EspessuraController;

Flight::route('/', function(){
    Header('Location: ./home.php');
	die;
});

### USUARIO
Flight::route('GET /api/BuscaUsuario/@id_usuario:[0-9]{1,9}', function($id_usuario){
	$_SESSION['id_usuario'] = $id_usuario;
	
	$objUsuario = new UsuarioController();	
	$objUsuario->buscaUsuario($id_usuario);
});

Flight::route('POST /api/CadastroUsuario', function(){
	$objUsuario = new UsuarioController();
	
    $request = Flight::request()->data->getData();
	
	$objUsuario->inserir($request);
    
});

Flight::route('POST /api/LoginUsuario', function(){
	$objUsuario = new UsuarioController();
	
    $request = Flight::request()->data->getData();
	
	$objUsuario->login($request);
    
});

Flight::route('POST /api/RecuperarSenha', function(){
	$objUsuario = new UsuarioController();
	
    $request = Flight::request()->data->getData();
	
	$objUsuario->recuperarSenha($request);
    
});

Flight::route('POST /api/TrocarSenha', function(){
	$objUsuario = new UsuarioController();
	
	$request = Flight::request()->data->getData();
	if (!isset($_SESSION['hash'])) {
		echo 'Sessão não carregada corretamente';
	}
	$request['hash'] = $_SESSION['hash'];
	$objUsuario->trocarSenha($request);
    
});

Flight::route('GET /api/LogoutUsuario', function(){
	session_unset();
    session_destroy();
	Header('./painel/login.php');
	die;
});

### MARCA > LINHA > COR
Flight::route('GET /api/BuscaMarcas', function(){
	$objMarca = new MarcaController();

	echo $objMarca->buscaMarcas();
    return;
});

Flight::route('GET /api/BuscaMarca/@id_marca:[0-9]{1,9}', function($id_marca){
	$objMarca = new MarcaController();

	echo $objMarca->buscaMarca($id_marca);
    return;
});

Flight::route('POST /api/InserirMarca/', function(){

	$request = Flight::request()->data->getData();

	$objMarca = new MarcaController();

	echo $objMarca->inserirMarca($request['descricao']);
    return;
});

Flight::route('POST /api/AlterarMarca/', function(){

	$request = Flight::request()->data->getData();

	$objMarca = new MarcaController();

	echo $objMarca->editarMarca($request);
    return;
});

Flight::route('GET /api/BuscaLinha/@id_marca:[0-9]{1,9}', function($id_marca){
	$objLinha = new LinhaController();
	echo $objLinha->buscaLinhas($id_marca);

	return;
});

Flight::route('GET /api/BuscaLinhaPorId/@id_linha:[0-9]{1,9}', function($id_linha){
	$objLinha = new LinhaController();	
	echo $objLinha->buscaLinha($id_linha);
    return;
});

Flight::route('POST /api/AlterarLinha/', function(){

	$request = Flight::request()->data->getData();

	$objLinha = new LinhaController();

	echo $objLinha->editarLinha($request);
    return;
});


Flight::route('POST /api/InserirLinha/', function(){

	$request = Flight::request()->data->getData();

	$objLinha = new LinhaController();

	echo $objLinha->inserirLinha($request['descricao'], $request['id_marca']);
    return;
});

Flight::route('GET /api/BuscaCor/@id_li:[0-9]{1,9}', function($id_linha){
	$objCor = new CorController();
	echo $objCor->buscaCores($id_linha);
	return;
});

Flight::route('GET /api/BuscaCorImagem/@id_cor:[0-9]{1,9}', function($id_cor){
	$objCor = new CorController();
	echo $objCor->buscaCor($id_cor);
	return;
});

Flight::route('GET /api/BuscaCorPorId/@id_cor:[0-9]{1,9}', function($id_cor){
	$objCor = new CorController();	
	echo $objCor->buscaCor($id_cor);
    return;
});

Flight::route('POST /api/AlterarCor/', function(){

	$request = Flight::request()->data->getData();

	$objCor = new CorController();

	echo $objCor->editarCor($request);
    return;
});

Flight::route('POST /api/InserirCor/', function(){

	$request = Flight::request()->data->getData();

	$objCor = new CorController();

	echo $objCor->inserirCor($request['descricao'], $request['id_linha'], '');
    return;
});

Flight::route('GET /api/BuscaHierarquiaMarcaLinhaCor/', function(){
	$draw = $_GET['draw'] ?: 1;	
	$length = $_GET['length'] ?: 50;
	$start = $_GET['start'] ?: 0;
	$search = $_GET['search']['value'] ?: '';	
	
	$objMarca = new MarcaController();
	
	$totalHierarquia = $objMarca->buscaHierarquiaTotal(['search' => $search]);
	$arrHierarquia = $objMarca->buscaHierarquia(['length' => $length, 'start' => $start, 'search' => $search]);
	
	$id_marca_atual = null;
	$id_linha_atual = null;
	$id_cor_atual 	= null;
	$arrLinhasTabela = [];
	if (is_array($arrHierarquia)) {
		foreach ($arrHierarquia as $linha) {
			if ($linha['id_marca'] != $id_marca_atual) {
				$arrLinhasTabela[] = array('item' => sprintf('%s', $linha['marca']), 'editar' => '<button class="btn btn-link editarMarca" role="link" type="button" name="op" value="'.$linha['id_marca'].'">Editar</button>', 'excluir' => '<button class="btn btn-link" role="link" type="button" name="op" value="'.$linha['id_marca'].'">Excluir</button>');				
				$id_marca_atual = $linha['id_marca'];
			}
			
			if ($linha['id_linha'] != $id_linha_atual) {
				$arrLinhasTabela[] = array('item' => sprintf('%s', $linha['marca'] . ' > ' . $linha['linha']), 'editar' => '<button class="btn btn-link editarLinha" type="button" name="op" value="'.$linha['id_linha'].'">Editar</button>', 'excluir' => '<button class="btn btn-link" role="link" type="button" name="op" value="'.$linha['id_linha'].'">Excluir</button>');
				$id_linha_atual = $linha['id_linha'];
			}
			
			if ($linha['id_cor'] != $id_cor_atual) {
				$arrLinhasTabela[] = array('item' => sprintf('%s', $linha['marca'] . ' > ' . $linha['linha'] . ' > ' . $linha['cor']), 'editar' => '<button class="btn btn-link editarCor" type="button" name="op" value="'.$linha['id_cor'].'">Editar</button>', 'excluir' => '<button class="btn btn-link" role="link" type="button" name="op" value="'.$linha['id_cor'].'">Excluir</button>');			
				$id_cor_atual= $linha['id_cor'];
			}
		}
	}
	
	echo json_encode(
		[
			'draw' => $draw,
			'recordsTotal' => $totalHierarquia['total'],
			'recordsFiltered' => $totalHierarquia['total'],
			'data' => $arrLinhasTabela
		]
	);
});

### CATEGORIAS > SUBCATEGORIAS
Flight::route('GET /api/BuscaCategorias', function(){
	CategoriaController::buscaCategorias();
});

Flight::route('GET /api/BuscaCategoria/(@id:[0-9]{1,9})', function($id_categoria){
	echo CategoriaController::buscaCategoria($id_categoria);
	return;
});

Flight::route('GET /api/BuscaSubCategorias/(@id:[0-9]{1,9})', function($id_categoria_pai){
	CategoriaController::buscaCategorias($id_categoria_pai);
});

Flight::route('GET /api/BuscaHierarquiaCategoriaSubcategoria/', function(){
	$draw = $_GET['draw'] ?: 1;	
	$length = $_GET['length'] ?: 50;
	$start = $_GET['start'] ?: 0;
	$search = $_GET['search']['value'] ?: '';	
	
	$totalHierarquia = CategoriaController::buscaArvoreCategoriasTotal(['search' => $search]);
	$arrHierarquia = json_decode(CategoriaController::buscaArvoreCategorias(['length' => $length, 'start' => $start, 'search' => $search]), true);
	
	$id_categoria_atual = null;
	$id_subcategoria_atual = null;
	$arrLinhasTabela = [];
	if (is_array($arrHierarquia)) {
		foreach ($arrHierarquia as $categoria) {
			$arrLinhasTabela[] = array('item' => sprintf('%s', $categoria['nome']), 'editar' => '<button class="btn btn-link editarCategoria" role="link" type="button" name="op" value="'.$categoria['id'].'">Editar</button>', 'excluir' => '<button class="btn btn-link" role="link" type="button" name="op" value="'.$categoria['id'].'">Excluir</button>');
			foreach ($categoria['subcategorias'] as $subcategoria) {
				$arrLinhasTabela[] = array('item' => sprintf('%s', $categoria['nome'] . ' > ' . $subcategoria['descricao']), 'editar' => '<button class="btn btn-link editarSubCategoria" type="button" name="op" value="'.$subcategoria['id_categoria'].'">Editar</button>', 'excluir' => '<button class="btn btn-link" role="link" type="button" name="op" value="'.$subcategoria['id_categoria'].'">Excluir</button>');
			}
		}
	}
	
	echo json_encode(
		[
			'draw' => $draw,
			'recordsTotal' => $totalHierarquia['total'],
			'recordsFiltered' => $totalHierarquia['total'],
			'data' => $arrLinhasTabela
		]
	);
});

Flight::route('POST /api/AlterarCategoria/', function(){

	$request = Flight::request()->data->getData();

	$objCategoria = new CategoriaController();

	echo $objCategoria->editarCategoria($request);
    return;
});

Flight::route('POST /api/InserirCategoria/', function(){

	$request = Flight::request()->data->getData();

	$objCategoria = new CategoriaController();

	echo $objCategoria->inserirCategoria($request['descricao'], isset($request['id_categoria_pai']) ? $request['id_categoria_pai'] : 0);
    return;
});

### MATERIAL

Flight::route('GET /api/BuscaCamposExibir/', function(){
	$arrColunas = [
					["data"=>"handle"],
					["data"=>"titulo"],
					["data"=>"categoria"],
					["data"=>"marca"],
					["data"=>"linha"],
					["data"=>"cor"],
					["data"=>"subcategoria"],
					["data"=>"quantidade"],
					["data"=>"situacao_anuncio"]
	];
	if (isset($_SESSION['grupo']) && ($_SESSION['grupo'] == 2)) {
		$arrColunas = array_merge($arrColunas, [["data" => "usuario"]]);
	}
	
	$arrColunas = array_merge(
					$arrColunas, [
						["data"=>"editar"],
						["data"=>"excluir"]
					]
				);

	echo json_encode($arrColunas);
});

Flight::route('GET /api/BuscaMaterial/@id_material:[0-9]{1,9}', function($id_material){
	$_SESSION['id_material'] = $id_material;
	$id_usuario = $_SESSION['user_id'] ?: 0;
	MaterialController::buscaMaterial($id_material, $id_usuario);
});

Flight::route('GET /api/ListaMateriais/', function(){
	$arrParams = [];
	$arrParams['draw'] = Flight::request()->query->draw;
	
	$arrParams['id_usuario'] = $_SESSION['user_id'] ?: null;
	if (Flight::request()->query->handle_usuario && isset($_SESSION['grupo']) && ($_SESSION['grupo'] == 2)) {
		$arrParams['id_usuario'] = Flight::request()->query->handle_usuario;
	}	
	
	MaterialController::buscaMateriais($arrParams);
});

Flight::route('POST /api/CadastroMaterial', function(){
	$request = Flight::request()->data->getData();
	
	$request['id_usuario'] = $_SESSION['user_id'] ?: null;
	MaterialController::inserir($request);
});

Flight::route('DELETE /api/ExcluirMaterial/@id_material:[0-9]{1,9}', function($id_material){
	$request = Flight::request()->data->getData();
	
	$id_usuario = $_SESSION['user_id'] ?: 0;
	MaterialController::excluir($id_material, $id_usuario);
});

### ESTADOS > MUNICIPIO
Flight::route('GET /api/BuscaMunicipio/(@codigo_uf:[0-9]{1,2})', function($codigo_uf){
	MunicipioController::buscaMunicipioPorEstado($codigo_uf);
});

Flight::route('GET /api/BuscaEstados/', function(){
	EstadoController::buscaEstados();
});

### EMAIL
Flight::route('POST /api/enviarEmail/', function(){
	$request = Flight::request()->data->getData();
	EmailController::enviar($request);
});

### ESPESSURAS
Flight::route('GET /api/BuscaEspessuras', function(){
	EspessuraController::buscaEspessuras();
});

Flight::route('GET /api/BuscaEspessura/(@id:[0-9]{1,9})', function($id_espessura){
	echo EspessuraController::buscaEspessura($id_espessura);
	return;
});

Flight::route('GET /api/BuscaHierarquiaEspessura/', function(){
	$draw = $_GET['draw'] ?: 1;	
	$length = $_GET['length'] ?: 50;
	$start = $_GET['start'] ?: 0;
	$search = $_GET['search']['value'] ?: '';	
	
	$totalHierarquia = EspessuraController::buscaArvoreEspessurasTotal(['search' => $search]);
	$arrHierarquia = json_decode(EspessuraController::buscaArvoreEspessuras(['length' => $length, 'start' => $start, 'search' => $search]), true);
	
	$arrLinhasTabela = [];
	if (is_array($arrHierarquia)) {
		foreach ($arrHierarquia as $espessura) {
			$arrLinhasTabela[] = array('item' => sprintf('%s', $espessura['nome']), 'editar' => '<button class="btn btn-link editarEspessura" role="link" type="button" name="op" value="'.$espessura['id'].'">Editar</button>', 'excluir' => '<button class="btn btn-link" role="link" type="button" name="op" value="'.$espessura['id'].'">Excluir</button>');
		}
	}
	
	echo json_encode(
		[
			'draw' => $draw,
			'recordsTotal' => $totalHierarquia['total'],
			'recordsFiltered' => $totalHierarquia['total'],
			'data' => $arrLinhasTabela
		]
	);
});

Flight::route('POST /api/AlterarEspessura/', function(){

	$request = Flight::request()->data->getData();

	$objEspessura = new EspessuraController();

	echo $objEspessura->editarEspessura($request);
    return;
});

Flight::route('POST /api/InserirEspessura/', function(){

	$request = Flight::request()->data->getData();

	$objEspessura = new EspessuraController();

	echo $objEspessura->inserirEspessura($request['valor']);
    return;
});

## ANUNCIOS

Flight::route('GET /api/BuscaMaterialAutoComplete/', function(){
	echo MaterialController::buscaMaterialAutoComplete($_GET['query']);
	return;
});

Flight::start();