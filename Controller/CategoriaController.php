<?php
namespace SuaMadeira;
use SuaMadeira\Model\Categoria;

class CategoriaController {
		
	public function __construct() {
		
	}
	
	public static function buscaCategorias($id_categoria_pai = 0) {
		$categoria = new Categoria();

		$categoria->situacao = 1;
		$categoria->id_categoria_pai = $id_categoria_pai;
		$arrCategorias = $categoria->listarCategorias();

		if (count($arrCategorias) > 0) {
			echo json_encode(
					$arrCategorias
			);
		}
	}
	
	public static function buscaArvoreCategorias($parametros = array()) {
		$categoria = new Categoria();

		$categoria->situacao = 1;
		$arrCategorias = array();
		
		
		foreach ($categoria->listarTodasCategorias() as $categoria) {
			if ($categoria['id_categoria_pai'] == 0) {
				$arrCategorias[$categoria['id_categoria']] = array('id' => $categoria['id_categoria'], 'nome' => $categoria['descricao'], 'subcategorias' => []);
			} else {
				$arrCategorias[$categoria['id_categoria_pai']]['subcategorias'][] = $categoria;
			}
		}
		
		if (count($arrCategorias) > 0) {
			return json_encode(
					$arrCategorias
			);
		}
	}
	
	public static function buscaArvoreCategoriasTotal($parametros = array()) {
		$categoria = new Categoria();
		$categoria->situacao = 1;
		
		return $categoria->listarTodasCategoriasTotal();
	}	

	public static function buscaCategoria($id_categoria) {
		$categoria = new Categoria();
		$categoria->id_categoria = $id_categoria;

		return json_encode(
			$categoria->buscaCategoria()
		);
	}
	
	public static function inserirCategoria($descricao, $id_categoria_pai) {
		$categoria = new Categoria();

		$categoria->descricao = $descricao;
		$categoria->situacao = 1;
		$categoria->id_categoria_pai = $id_categoria_pai;
		$categoria->dt_criacao = date('Y-m-d H:i:s');
		$categoria->dt_modificado = date('Y-m-d H:i:s');
		$id_categoria = $categoria->inserir();
		return $id_categoria;
	}
	
	public static function editarCategoria($request) {
		$categoria = new Categoria();
		$categoria->descricao = $request['descricao'];
		$categoria->id_categoria_pai = isset($request['id_categoria_pai']) ? $request['id_categoria_pai'] : 0;
		$categoria->id_categoria = $request['handle'];
		$categoria->situacao = 1;
		$categoria->dt_modificado = date('Y-m-d H:i:s');
		$id_categoria = $categoria->editar();
		return $request['handle'];
	}	
}
?>
