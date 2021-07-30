<?php
namespace SuaMadeira;
use SuaMadeira\Model\Material;
use SuaMadeira\Model\Municipio;

class MaterialController {
		
	public function __construct() {
		
	}
	
	public static function inserir($request) {
		$material = new Material();
		$materialId = 0;
		$retorno = null;
		$retornoConversaoImagem = null;
		$msgRetornoErro = '';

		$validaFormulario = self::validaDados($request);

		if ($validaFormulario['success'] == 'false') {
			echo json_encode(
					[
						'success' => 'false',
						'message' => implode('<br>', $validaFormulario['message'])
					]
				);
			return;			
		}
	
		if (isset($request['handle_material'])) {
			$materialId = $request['handle_material'];
			$material->id = $materialId;
			unset($request['handle_material']);
		}

		if (isset($request['arquivoExcluir'])) {
			if (is_array($request['arquivoExcluir'])) {
				foreach ($request['arquivoExcluir'] as $arquivoExcluir) {
					$material->id_material_imagem = $arquivoExcluir;
					$material->excluirImagem();
				}
			}
			unset($request['arquivoExcluir']);
			unset($material->id_material_imagem);
		}

		foreach ($request as $req_k => $req_v) {
			$material->{$req_k} = $req_v;
		}	
	
		$material->dt_modificado = date('Y-m-d H:i:s');
		if ($materialId > 0) {
			$retorno = $material->editar();
		} else {
			$material->dt_criacao = date('Y-m-d H:i:s');
			$materialId = $material->inserir();
		}

		if (sizeof($_FILES) > 0) {
			$arrArquivos = self::reArrayFiles($_FILES['files']);
			
			$qtdImagens = 0;
			foreach ($arrArquivos as $key => $value) {
				if ($qtdImagens > 1) {
					$msgRetornoErro .= 'Somente foram carregadas 2 imagens, devido ao limite!';
					continue;
				}
				$nome_arquivo = $value['name'];
				$tipo_arquivo = $value['type'];
				$temp_arquivo = $value['tmp_name'];
				$size_arquivo = $value['size'];
				$extensao_arquivo = pathinfo($nome_arquivo, PATHINFO_EXTENSION);			

				if (!in_array($tipo_arquivo, [ 'image/jpg', 'image/jpeg', 'image/gif', 'image/png' ])) {
					$msgRetornoErro .= 'Formato da imagem '.$nome_arquivo.' invalido!';
					continue;
				}
				
				if($size_arquivo > 2097152) {
					$msgRetornoErro .= 'Tamanho da imagem '.$nome_arquivo.' invalido!';
					continue;
				}

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

				$nome_arquivo = $materialId.'-'.$key.'.'.$extensao_arquivo;
				$src = imagecreatefromstring(file_get_contents($temp_arquivo));
				$dst = imagecreatetruecolor($width,$height);
				imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$tamanhoImagem[0],$tamanhoImagem[1]);
				imagedestroy($src);

				if (in_array($tipo_arquivo, [ 'image/png' ])) {
					$retornoConversaoImagem = imagepng($dst, "./imagens/".$nome_arquivo);
				}
				if (in_array($tipo_arquivo, [ 'image/jpg', 'image/jpeg' ])) {
					$retornoConversaoImagem = imagejpeg($dst, "./imagens/".$nome_arquivo);
				}
				if (in_array($tipo_arquivo, [ 'image/gif' ])) {
					$retornoConversaoImagem = imagegif ($dst, "./imagens/".$nome_arquivo);
				}

				imagedestroy($dst);

				$material->id = $materialId;
				$material->arquivo = $nome_arquivo;
				$material->inserirImagem();
				
				$qtdImagens++;
			}
		}
		
		if ($materialId > 0 || $retorno == true) {
			if (!empty($msgRetornoErro)) {
				$msgRetornoErro = '<br> <strong>Atenção, verifique a seguinte situação: </strong> <br>' . $msgRetornoErro;
			}
            echo json_encode(
                    [
                        'success' => 'true',
                        'message' => 'Dados salvo com sucesso!'.$msgRetornoErro
                    ]
                );
        } else {
            echo json_encode(
                    [
                        'success' => 'false',
                        'message' => 'Erro ao salvar o material!'.$msgRetornoErro
                    ]
                );        
        }
		return;
	}
	
	public static function excluir($id_material, $id_usuario) {
		$material = new Material();
		
		$material->id = $id_material;
		$material->id_usuario = $id_usuario;

		$arrMaterial = $material->buscaMaterial();
		
		if (count($arrMaterial) > 0) {
			$material->excluir();
			echo json_encode(
				array('success' => 'true', 'message' => 'Material removido com sucesso!')
			);
			die;			
		} else {
			echo json_encode(
				array('success' => 'false', 'message' => 'Material não encontrado!')
			);
			die;
		}		
		
	}
	
	public static function reArrayFiles($arr) {

		foreach( $arr as $key => $all ){
			foreach( $all as $i => $val ){
				$new[$i][$key] = $val;   
			}   
		}
		return $new;
	}	

	public static function buscaMaterial($id_material, $id_usuario) {
		$material = new Material();
		$material->id = $id_material;
		$material->id_usuario = $id_usuario;

		$arrMaterial = $material->buscaMaterial();

		if (count($arrMaterial) > 0) {
			$arrMaterialImagem = $material->buscaMaterialImagem();
			if (count($arrMaterialImagem) > 0) {
				$arrMaterial['imagem'] = $arrMaterialImagem;
			}
			echo json_encode(
				array_merge(
					$arrMaterial,
					array(
						'erro' => ''
					)
				)
			);
		} else {
			echo json_encode(
				array('erro' => 'Material não encontrado!')
			);
			die;
		}
	}

	public static function buscaMateriais($arrParams) {
		$draw = $arrParams['draw'] ?: 1;		
		$material = new Material();
		
		$material->id_usuario = $arrParams['id_usuario'] ?: null;
		$arrMaterial = $material->buscaMateriais();
		
		$arrRetornoListaMateriais = [];
		if (count($arrMaterial) > 0) {
			foreach ($arrMaterial as $material) {
				$arrRetornoListaMateriais[] = [
									'handle' => $material['id'],
									'usuario' => 'Denis',
									'titulo' => $material['titulo'],
									'quantidade' => $material['quantidade'],
									'situacao_anuncio' => $material['situacao_anuncio'] == 1 ? 'Sim' : 'Não',
									'marca' => $material['marca'],
									'linha' => $material['linha'],
									'cor' => $material['cor'],
									'categoria' => $material['categoria'],
									'subcategoria' => $material['subcategoria'],
									'editar' => sprintf('<input class="btn btn-secondary editarMaterial" type="button" handleMaterial="%s" value="Editar">', $material['id']),
									'excluir' => sprintf('<input class="btn btn-secondary excluirMaterial" type="button" handleMaterial="%s" value="Excluir">', $material['id'])
								 ];
			}
		}
		
		echo json_encode(
			[
				'draw' => $draw,
				'recordsTotal' => count($arrRetornoListaMateriais),
				'recordsFiltered' => count($arrRetornoListaMateriais),
				'data' => $arrRetornoListaMateriais
			]
		);
	}
	
	public static function buscaAnuncioRecente() {
		$objMaterial = new Material();

		$arrMaterial = $objMaterial->buscaAnuncioRecente();

		if (count($arrMaterial) > 0) {
			foreach ($arrMaterial as $key => $material) {
				$objMaterial->id = $material['id'];
				$arrMaterialImagem = $objMaterial->buscaMaterialImagem();	
				if ($material['cor_imagem'] != null) {
					$arrMaterial[$key]['imagem'] = [
								'handle' => $material['id_cor'],
								'nome_arquivo' => 'categorias/' . $material['cor_imagem']
					];
				} else if (count($arrMaterialImagem) > 0) {
					$arrMaterial[$key]['imagem'] = $arrMaterialImagem[0];
				}				
			}
			
			return json_encode(
				$arrMaterial
			);
		}
	}

	public static function buscaAnuncioPorCategoria($id_categoria) {
		$objMaterial = new Material();

		$objMaterial->id_categoria = $id_categoria;
		$objMaterial->id_subcategoria = $id_categoria;
		$arrMaterial = $objMaterial->buscaAnuncioPorCategoria();
		
		if (count($arrMaterial) > 0) {
			foreach ($arrMaterial as $key => $material) {
				$objMaterial->id = $material['id'];
				$arrMaterialImagem = $objMaterial->buscaMaterialImagem();

				if ($material['cor_imagem'] != null) {
					$arrMaterial[$key]['imagem'] = [
								'handle' => $material['id_cor'],
								'nome_arquivo' => 'categorias/' . $material['cor_imagem']
					];
				} else if (count($arrMaterialImagem) > 0) {
					$arrMaterial[$key]['imagem'] = $arrMaterialImagem[0];
				}			
			}
		}
		
		return json_encode(
			$arrMaterial
		);		
	}	
	
	private static function validaDados($frmDados) {
		$frm_valido = 'true';
		$arrRetorno = ['success' => true, 'message' => [] ];
		$material = new Material();

		$arrCampos = json_decode($material->dados(), true);

		foreach ($arrCampos as $key => $value) {
			if (!isset($frmDados[$key]) || empty($frmDados[$key])) {
				if ($value['obrigatorio'] == 'true') {
					$arrRetorno['message'][] = $value['mensagem'];
					$frm_valido = 'false';
				}
			}
		}
		
		$arrRetorno['success'] = $frm_valido;
		
		return $arrRetorno;
		
	}

	public static function buscaAnuncio($id_material) {
		$material = new Material();
		$material->id = $id_material;

		$arrMaterial = $material->buscaMaterial();

		if (count($arrMaterial) > 0) {
			$arrMaterialImagem = $material->buscaMaterialImagem();
			if (count($arrMaterialImagem) > 0) {
				$arrMaterial[0]['imagem'] = $arrMaterialImagem;
			} else {
				$arrMaterial[0]['imagem'] = [[
					'handle' => 0,
					'nome_arquivo' => ''
				]];
			}

			if ($arrMaterial[0]['cor_imagem'] != null) {
				$arrMaterial[0]['imagem'] = array_merge(array(array('handle' => $arrMaterial[0]['id_cor'], 'nome_arquivo' => 'categorias/' . $arrMaterial[0]['cor_imagem'])),$arrMaterial[0]['imagem']);
			}
			
			return json_encode(
				$arrMaterial[0]
			);
		}
	}	

	public static function buscaAnuncioPorAnunciante($id_usuario) {
		$objMaterial = new Material();

		$objMaterial->id_usuario = $id_usuario;
		$arrMaterial = $objMaterial->buscaAnuncioPorAnunciante();
		
		if (count($arrMaterial) > 0) {
			foreach ($arrMaterial as $key => $material) {
				$objMaterial->id = $material['id'];
				$arrMaterialImagem = $objMaterial->buscaMaterialImagem();	
				if (count($arrMaterialImagem) > 0) {
					$arrMaterial[$key]['imagem'] = $arrMaterialImagem[0];
				}				
			}
		}
		
		return json_encode(
			$arrMaterial
		);		
	}	
	
	public static function buscaAnunciosComFiltro($arrFitros) {
		$objMaterial = new Material();
		$objMunicipio = new Municipio();

		$arrFiltrosPossiveis = array(
			'categoria' => 'id_categoria', 
			'subcategoria' => 'id_subcategoria', 
			'marca' => 'id_marca', 
			'linha' => 'id_linha', 
			'cor' => 'id_cor',
			'cidade' => '', 
			'limite_busca' => '',
			'palavra_chave' => ''
		);
		$palavra_chave = '';
		$arrFiltroAnuncio = array();
		foreach ($arrFitros as $key => $value) {
			if ($key == 'cidade') {
				$objMunicipio->codigo_ibge = $value;
				$arrMunicipio = $objMunicipio->consultaMunicipio();
				$arrFiltroAnuncio['id_municipio'] = $value;
				$arrFiltroAnuncio['latitude'] = $arrMunicipio[0]['latitude'];
				$arrFiltroAnuncio['longitude'] = $arrMunicipio[0]['longitude'];
				continue;
			}
			
			if ($key == 'limite_busca' && $value > 0) {
				$arrFiltroAnuncio['limite_busca'] = $value;
			}
			if ($key == 'palavra_chave') {
				$arrFiltroAnuncio['palavra_chave']  = $value;
			}

			if ($value == '0') {
				continue;
			}
			$objMaterial->{$arrFiltrosPossiveis[$key]} = $value;
		}
		
		$arrMaterial = $objMaterial->buscaAnuncioComFiltro($arrFiltroAnuncio);
		
		if (count($arrMaterial) > 0) {
			foreach ($arrMaterial as $key => $material) {
				$objMaterial->id = $material['id'];
				$arrMaterialImagem = $objMaterial->buscaMaterialImagem();	
				
				
				if (count($arrMaterialImagem) > 0) {
					$arrMaterial[$key]['imagem'] = $arrMaterialImagem[0];
				}								
			}
		}
		
		return json_encode(
			$arrMaterial
		);		
	}
	
	public static function buscaMaterialAutoComplete($palavra_chave) {
		$objMaterial = new Material();
		
		$arrMaterial = $objMaterial->buscaMaterialAutoComplete($palavra_chave);

		$arrRetorno = [];
		if (count($arrMaterial) > 0) {
			foreach ($arrMaterial as $key => $material) {
				$arrRetorno[] = [
					'value' => $material['descricao'],
					'data' => [
						'category' => ' > ' . $material['categoria']
					]
				];
			}
		}
		
		return json_encode(
			['suggestions' => $arrRetorno]
		);		
	}	
}
?>
