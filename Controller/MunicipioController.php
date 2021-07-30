<?php
namespace SuaMadeira;
use SuaMadeira\Model\Municipio;

class MunicipioController {
		
	public function __construct() {
		
	}
	
	public static function buscaMunicipioPorEstado($codigo_uf) {
		$municipio = new Municipio();
		
		$municipio->codigo_uf = $codigo_uf;
		$arrMunicipios = $municipio->listarPorEstado();

		if (count($arrMunicipios) > 0) {
			echo json_encode(
				$arrMunicipios
			);
		}
	}
	
	public static function buscaMunicipiosComAnuncio() {
		$municipio = new Municipio();
		
		$arrMunicipiosComAnuncio = $municipio->listarMunicipiosComAnuncio();
		
		$arrRetorno = array();
		$_uf = null;
		if (count($arrMunicipiosComAnuncio) > 0) {
			foreach ($arrMunicipiosComAnuncio as $value) {
				if ($_uf != $value['uf']) {
					$arrRetorno[$value['uf']] = array();
				}
				
				$arrRetorno[$value['uf']][$value['codigo_ibge']] = $value['nome'];
				
				$_uf = $value['uf'];
			}
		}		

		return json_encode(
			$arrRetorno
		);		
	}
		
}
?>
