<?php
namespace SuaMadeira;
use SuaMadeira\Model\Espessura;

class EspessuraController {
		
	public function __construct() {
		
	}
	
	public static function buscaEspessuras() {
		$espessura = new Espessura();

		$espessura->situacao = 1;
		$arrEspessuras = $espessura->listarEspessuras();

		if (count($arrEspessuras) > 0) {
			echo json_encode(
					$arrEspessuras
			);
		}
	}
	
	public static function buscaEspessurasTotal($parametros = array()) {
		$espessura = new Espessura();
		
		return $espessura->listarTodasEspessurasTotal();
	}

	public static function buscaArvoreEspessuras($parametros = array()) {
		$espessura = new Espessura();

		$arrEspessuras = array();
		
		
		foreach ($espessura->listarTodasEspessuras() as $espessura) {
			$arrEspessuras[$espessura['id_espessura']] = array('id' => $espessura['id_espessura'], 'nome' => $espessura['valor']);
		}
		
		if (count($arrEspessuras) > 0) {
			return json_encode(
					$arrEspessuras
			);
		}
	}
	
	public static function buscaArvoreEspessurasTotal($parametros = array()) {
		$espessura = new Espessura();
		
		return $espessura->listarTodasEspessurasTotal();
	}	

	public static function buscaEspessura($id_espessura) {
		$espessura = new Espessura();
		$espessura->id_espessura = $id_espessura;

		return json_encode(
			$espessura->buscaEspessura()
		);
	}
	
	public static function inserirEspessura($valor) {
		$espessura = new Espessura();

		$espessura->valor = $valor;
		$espessura->situacao = 1;
		$espessura->dt_criacao = date('Y-m-d H:i:s');
		$espessura->dt_modificado = date('Y-m-d H:i:s');
		$id_espessura = $espessura->inserir();
		return $id_espessura;
	}
	
	public static function editarEspessura($request) {
		$espessura = new Espessura();
		$espessura->valor = $request['valor'];
		$espessura->situacao = 1;
		$espessura->dt_modificado = date('Y-m-d H:i:s');
		$espessura->id_espessura = $request['handle'];
		$id_espessura = $espessura->editar();
		return $request['handle'];
	}
}
?>
