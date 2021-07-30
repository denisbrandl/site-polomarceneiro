<?php
namespace SuaMadeira;
use SuaMadeira\Model\Estado;

class EstadoController {
		
	public function __construct() {
		
	}
	
	public static function buscaEstados() {
		$estado = new Estado();

		$arrEstados = $estado->listar();

		if (count($arrEstados) > 0) {
			echo json_encode(
				$arrEstados
			);
		}
	}
		
}
?>
