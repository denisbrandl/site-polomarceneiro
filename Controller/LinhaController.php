<?php
namespace SuaMadeira;
use SuaMadeira\Model\Linha;

class LinhaController {
		
	public function __construct() {
		
	}
	
	public function buscaLinhas($id_marca) {
		$linha = new Linha();
		
		$linha->situacao = 1;
		$linha->id_marca = $id_marca;

		$arrLinhas = $linha->listarPorMarca();

		if (count($arrLinhas) > 0) {
			return json_encode(
				$arrLinhas
			);
		}
	}

	public function buscaLinha($id_linha) {
		$linha = new Linha();
		$linha->id_linha = $id_linha;
		$arrLinha = $linha->listarLinha();

		if (count($arrLinha) > 0) {
			return json_encode(
				$arrLinha
			);
		}
	}	

	public static function inserirLinha($descricao, $id_marca) {
		$linha = new Linha();

		$linha->descricao = $descricao;
		$linha->situacao = 1;
		$linha->id_marca = $id_marca;
		$linha->dt_criacao = date('Y-m-d H:i:s');
		$linha->dt_modificado = date('Y-m-d H:i:s');
		$id_linha = $linha->inserir();
		return $id_linha;
	}
	
	public static function editarLinha($request) {
		$linha = new Linha();
		$linha->descricao = $request['descricao'];
		$linha->id_marca = $request['id_marca'];
		$linha->id_linha = $request['handle'];
		$linha->situacao = 1;
		$linha->dt_modificado = date('Y-m-d H:i:s');
		$id_linha = $linha->editar();
		return $id_linha;
	}	
		
}
?>
