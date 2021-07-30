<?php
namespace SuaMadeira;
use SuaMadeira\Model\Marca;

class MarcaController {
		
	public function __construct() {
		
	}
	
	public function buscaMarcas() {
		$marca = new Marca();
		
		$marca->situacao = 1;
		$arrMarcas = $marca->listar();

		if (count($arrMarcas) > 0) {
			return json_encode(
				$arrMarcas
			);
		}
	}

	public function buscaMarca($id_marca) {
		$marca = new Marca();
		
		$marca->id_marca = $id_marca;
		$arrMarcas = $marca->listarMarca();

		if (count($arrMarcas) > 0) {
			return json_encode(
				$arrMarcas
			);
		}
	}	
	
	public function buscaHierarquia($parametros) {
		$marca = new Marca();
		$arrHierarquia = $marca->listarHierarquia($parametros);

		if (count($arrHierarquia) > 0) {
			return $arrHierarquia;
		}
	}	
	
	public function buscaHierarquiaTotal($parametros = array()) {
		$marca = new Marca();
		$arrHierarquia = $marca->totalHierarquia($parametros);

		if (count($arrHierarquia) > 0) {
			return $arrHierarquia;
		}
	}	

	public static function inserirMarca($descricao) {
		$marca = new Marca();

		$marca->descricao = $descricao;
		$marca->situacao = 1;
		$marca->dt_criacao = date('Y-m-d H:i:s');
		$marca->dt_modificado = date('Y-m-d H:i:s');
		$id_marca = $marca->inserir();
		return $id_marca;
	}

	public static function editarMarca($request) {
		$marca = new Marca();
		$marca->descricao = $request['descricao'];
		$marca->id_marca = $request['handle'];
		$marca->situacao = 1;
		$marca->dt_modificado = date('Y-m-d H:i:s');
		$id_marca = $marca->editar();
		return $id_marca;
	}	
		
}
?>
