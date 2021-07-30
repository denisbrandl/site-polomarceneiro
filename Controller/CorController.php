<?php
namespace SuaMadeira;
use SuaMadeira\Model\Cor;

class CorController {
		
	public function __construct() {
		
	}
	
	public function buscaCores($id_linha) {
		$cor = new Cor();
		
		$cor->situacao = 1;
		$cor->id_linha = $id_linha;
		$arrCors = $cor->listarPorLinha();

		if (count($arrCors) > 0) {
			return json_encode(
					$arrCors
			);
		}
	}

	public function buscaCor($id_cor) {
		$cor = new Cor();
		
		$cor->id_cor = $id_cor;
		$arrCors = $cor->listarCor();

		if (count($arrCors) > 0) {
			return json_encode(
					array(
						'id_cor' => $arrCors[0]['id_cor'],
						'id_linha' => $arrCors[0]['id_linha'],
						'id_marca' => $arrCors[0]['id_marca'],
						'descricao' => $arrCors[0]['descricao'],
						'imagem' => $arrCors[0]['imagem']
					)
			);
		}		
	}

	public static function inserirCor($descricao, $id_linha, $imagem = '') {
		$cor = new Cor();

		$cor->descricao = $descricao;
		$cor->situacao = 1;
		$cor->id_linha = $id_linha;
		$cor->imagem = $imagem;
		$cor->dt_criacao = date('Y-m-d H:i:s');
		$cor->dt_modificado = date('Y-m-d H:i:s');
		$id_cor = $cor->inserir();

		if (sizeof($_FILES) > 0) {
			$arrArquivos = self::reArrayFiles($_FILES['files']);
			foreach ($arrArquivos as $key => $value) {
				$nome_arquivo = $value['name'];
				$tipo_arquivo = $value['type'];
				$temp_arquivo = $value['tmp_name'];
				$extensao_arquivo = pathinfo($nome_arquivo, PATHINFO_EXTENSION);

				$tamanhoImagem = getimagesize($temp_arquivo);
				
				$relacaoTamanhoImagem = $tamanhoImagem[0]/$tamanhoImagem[1];

				if( $relacaoTamanhoImagem > 1) {
					$width = 200;
					$height = 200/$relacaoTamanhoImagem;
				}
				else {
					$width = 200*$relacaoTamanhoImagem;
					$height = 200;
				}
				$width = 500;
				$height = 500;				

				$nome_arquivo = 'cat-'.$id_cor.'-0.'.$extensao_arquivo;
				$src = imagecreatefromstring(file_get_contents($temp_arquivo));
				$dst = imagecreatetruecolor($width,$height);
				imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$tamanhoImagem[0],$tamanhoImagem[1]);
				imagedestroy($src);

				if (in_array($tipo_arquivo, [ 'image/png' ])) {
					$retornoConversaoImagem = imagepng($dst, "./imagens/categorias/".$nome_arquivo);
				}
				if (in_array($tipo_arquivo, [ 'image/jpg', 'image/jpeg' ])) {
					$retornoConversaoImagem = imagejpeg($dst, "./imagens/categorias/".$nome_arquivo);
				}
				if (in_array($tipo_arquivo, [ 'image/gif' ])) {
					$retornoConversaoImagem = imagegif ($dst, "./imagens/categorias/".$nome_arquivo);
				}

				imagedestroy($dst);
			}
		}

		$cor->id_cor = $id_cor;
		$cor->imagem = $nome_arquivo;
		$cor->atualizarImagem();

		return $id_cor;
	}
	
	public static function editarCor($request) {
		$cor = new Cor();

		$cor->id_cor = $request['handle'];
		$arrCor = $cor->listarCor();

		$cor->descricao = $request['descricao'];
		$cor->id_linha = $request['id_linha'];
		$cor->id_cor = $request['handle'];
		$cor->situacao = 1;
		$cor->dt_modificado = date('Y-m-d H:i:s');
		if (sizeof($_FILES) > 0) {
			$arrArquivos = self::reArrayFiles($_FILES['files']);

			if (file_exists("./imagens/categorias/".$arrCor[0]['imagem'])) {
				unlink("./imagens/categorias/".$arrCor[0]['imagem']);
			}

			foreach ($arrArquivos as $key => $value) {
				$nome_arquivo = $value['name'];
				$tipo_arquivo = $value['type'];
				$temp_arquivo = $value['tmp_name'];
				$extensao_arquivo = pathinfo($nome_arquivo, PATHINFO_EXTENSION);

				$tamanhoImagem = getimagesize($temp_arquivo);
				
				$relacaoTamanhoImagem = $tamanhoImagem[0]/$tamanhoImagem[1];

				if( $relacaoTamanhoImagem > 1) {
					$width = 200;
					$height = 200/$relacaoTamanhoImagem;
				}
				else {
					$width = 200*$relacaoTamanhoImagem;
					$height = 200;
				}
				$width = 500;
				$height = 500;				

				$nome_arquivo = 'cat-'.$request['handle'].'-0.'.$extensao_arquivo;
				$src = imagecreatefromstring(file_get_contents($temp_arquivo));
				$dst = imagecreatetruecolor($width,$height);
				imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$tamanhoImagem[0],$tamanhoImagem[1]);
				imagedestroy($src);

				if (in_array($tipo_arquivo, [ 'image/png' ])) {
					$retornoConversaoImagem = imagepng($dst, "./imagens/categorias/".$nome_arquivo);
				}
				if (in_array($tipo_arquivo, [ 'image/jpg', 'image/jpeg' ])) {
					$retornoConversaoImagem = imagejpeg($dst, "./imagens/categorias/".$nome_arquivo);
				}
				if (in_array($tipo_arquivo, [ 'image/gif' ])) {
					$retornoConversaoImagem = imagegif ($dst, "./imagens/categorias/".$nome_arquivo);
				}

				imagedestroy($dst);
			}
		}

		$cor->imagem = $nome_arquivo;
		$id_cor = $cor->editar();

		return $id_cor;
	}

	public static function reArrayFiles($arr) {

		foreach( $arr as $key => $all ){
			foreach( $all as $i => $val ){
				$new[$i][$key] = $val;   
			}   
		}
		return $new;
	}	
		
	public static function atualizarImagem($id) {
		$cor = new Cor();

		$cor->id_cor = $id;
		$cor->imagem = 'cat-'.$id.'-0.jpg';
		$id_cor = $cor->atualizarImagem();
		return $id_cor;
	}			
}
?>
