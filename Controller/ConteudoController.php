<?php
namespace SuaMadeira;
use SuaMadeira\Model\Conteudo;

class ConteudoController {
		
	public function __construct() {
		
	}
	
	public static function buscaConteudo($codigo_conteudo) {
		$objConteudo = new Conteudo();
		
		$objConteudo->id_conteudo = $codigo_conteudo;
		$arrConteudo = $objConteudo->consultaConteudo();

		if (count($arrConteudo) > 0) {
			return json_encode(
				$arrConteudo
			);
		}
	}
		
}
?>
