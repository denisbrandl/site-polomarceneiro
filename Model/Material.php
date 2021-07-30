<?php
namespace SuaMadeira\Model;
use SuaMadeira\Model\Conexao;
use PDO;

class Material {

	public $id;
	public $titulo;
	public $descricao;
	public $id_categoria;
	public $id_subcategoria;
	public $id_marca;
	public $id_linha;
	public $id_cor;
	public $quantidade;
	public $quantidade_venda;
	public $tipo_venda;
	public $unidade_medida;
	public $largura;
	public $altura;
	public $id_espessura;
	public $profundidade;
	public $peso;
	public $situacao_anuncio;
	public $id_usuario;
	public $dt_criacao;
	public $dt_modificado;
	public $arquivo;
	public $id_material_imagem;
	public $id_municipio;


	public function inserir() {
		try {
			$db = Conexao::getInstance();

			$sql = 'INSERT INTO material (
						titulo,
						descricao,
						id_categoria,
						id_subcategoria,
						id_marca,
						id_linha,
						id_cor,
						quantidade,
						quantidade_venda,
						unidade_medida,
						tipo_venda,
						largura,
						altura,
						id_espessura,
						profundidade,
						peso,
						situacao_anuncio,
						id_usuario,
						dt_criacao,
						dt_modificado						
					) VALUES 
					(
						:titulo,
						:descricao,
						:id_categoria,
						:id_subcategoria,
						:id_marca,
						:id_linha,
						:id_cor,
						:quantidade,
						:quantidade_venda,
						:tipo_venda,
						:unidade_medida,
						:largura,
						:altura,
						:id_espessura,
						:profundidade,
						:peso,
						:situacao_anuncio,
						:id_usuario,
						:dt_criacao,
						:dt_modificado						
					)';

			$stm = $db->prepare($sql);

            $stm->bindParam(':titulo',              $this->titulo);
            $stm->bindParam(':descricao',              $this->descricao);
            $stm->bindParam(':id_categoria',        $this->id_categoria);
            $stm->bindParam(':id_subcategoria',     $this->id_subcategoria);
            $stm->bindParam(':id_marca',            $this->id_marca);
            $stm->bindParam(':id_linha',            $this->id_linha);
            $stm->bindParam(':id_cor',              $this->id_cor);
            $stm->bindParam(':quantidade',          $this->quantidade);
            $stm->bindParam(':quantidade_venda',    $this->quantidade_venda);
            $stm->bindParam(':unidade_medida',      $this->unidade_medida);
            $stm->bindParam(':tipo_venda',			$this->tipo_venda);
            $stm->bindParam(':largura',			    $this->largura);
            $stm->bindParam(':altura',			    $this->altura);
            $stm->bindParam(':id_espessura',			$this->id_espessura);
            $stm->bindParam(':profundidade',		$this->profundidade);
            $stm->bindParam(':peso',			    $this->peso);
            $stm->bindParam(':situacao_anuncio',	$this->situacao_anuncio);
            $stm->bindParam(':id_usuario',			$this->id_usuario);
			$stm->bindParam(':dt_criacao', $this->dt_criacao);
			$stm->bindParam(':dt_modificado', $this->dt_modificado);
			
			$stm->execute();
			return $db->lastInsertId();
			
		} catch (Exception $e) {
            $stm->debugDumpParams();
			print $e->getMessage();
		}
	}
	
	public function editar() {
		try {
			$db = Conexao::getInstance();

			$sql = 'UPDATE
						material 
					SET
						titulo=:titulo,
						descricao=:descricao,
						id_subcategoria=:id_categoria,
						id_subcategoria=:id_subcategoria,
						id_marca=:id_marca,
						id_linha=:id_linha,
						id_cor=:id_cor,
						quantidade=:quantidade,
						quantidade_venda=:quantidade_venda,
						tipo_venda=:tipo_venda,
						unidade_medida=:unidade_medida,
						largura=:largura,
						altura=:altura,
						id_espessura=:id_espessura,
						profundidade=:profundidade,
						peso=:peso,
						situacao_anuncio=:situacao_anuncio,
						dt_modificado=:dt_modificado
					WHERE
						id=:id';

			$stm = $db->prepare($sql);

            $data = array(
				'titulo' => $this->titulo,
				'descricao' => $this->descricao,
				'id_categoria' => $this->id_categoria,
				'id_subcategoria' => $this->id_subcategoria,
				'id_marca' => $this->id_marca,
				'id_linha' => $this->id_linha,
				'id_cor' => $this->id_cor,
				'quantidade' => $this->quantidade,
				'quantidade_venda' => $this->quantidade_venda,
				'tipo_venda' => $this->tipo_venda,
				'unidade_medida' => $this->unidade_medida,
				'largura' => $this->largura,
				'altura' => $this->altura,
				'id_espessura' => $this->id_espessura,
				'profundidade' => $this->profundidade,
				'peso' => $this->peso,
				'situacao_anuncio' => $this->situacao_anuncio,
				'dt_modificado' => $this->dt_modificado,
				'id' => $this->id
			);
			
			$stm->execute($data);
			return true;
		} catch (Exception $e) {
            $stm->debugDumpParams();
			print $e->getMessage();
		}
	}	
	
	public function excluir() {
		try {
			$db = Conexao::getInstance();

			$sql = 'DELETE FROM
						material
					WHERE
						id=:id AND 
						id_usuario=:id_usuario
					';

			$stm = $db->prepare($sql);

            $data = array(
				'id' => $this->id,
				'id_usuario' => $this->id_usuario,
			);
			
			$stm->execute($data);
			return true;
		} catch (Exception $e) {
            $stm->debugDumpParams();
			print $e->getMessage();
		}
	}	

	public function buscaMaterial() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						material.id,
						material.titulo,
						material.descricao,
						material.quantidade,
						material.quantidade_venda,
						material.situacao_anuncio,
						material.largura,
						material.altura,
						material.id_espessura,
						espessura.valor as espessura,
						material.profundidade,
						material.tipo_venda,
						material.id_categoria,
						material.id_subcategoria,
						material.id_marca,
						material.id_linha,
						material.id_cor,
						(
							CASE 
								WHEN material.tipo_venda = 1 THEN "Somente todo estoque"
								WHEN material.tipo_venda = 2 THEN "Venda parcial"
								WHEN material.tipo_venda = 3 THEN "Venda parcial ou estoque inteiro"
							END 
						) as "descricao_situacao_venda",
						marca.descricao as marca,
						linha.descricao as linha,
						cor.descricao as cor,
						cor.imagem as cor_imagem,
						categoria.descricao as categoria,
						subcategoria.descricao as subcategoria,
						usuarios.nome_fantasia,
						municipios.nome as cidade,
						estados.uf as uf,
						usuarios.telefone,
						usuarios.id as handle_usuario, 
						usuarios.email
					FROM
						material
						LEFT JOIN espessura ON (material.id_espessura = espessura.id_espessura)
						LEFT JOIN marca ON (material.id_marca = marca.id_marca)
						LEFT JOIN linha ON (material.id_linha = linha.id_linha)
						LEFT JOIN cor ON (material.id_cor = cor.id_cor)
						LEFT JOIN categoria ON (material.id_categoria = categoria.id_categoria)
						LEFT JOIN categoria subcategoria ON (material.id_subcategoria = subcategoria.id_categoria)
						INNER JOIN usuarios ON (material.id_usuario = usuarios.id)
						INNER JOIN municipios ON (usuarios.cidade = municipios.codigo_ibge)
						INNER JOIN estados ON (usuarios.uf = estados.codigo_uf)
					WHERE
						material.id = :id
					';

			
			$arrExecute = array(':id' => $this->id);
			
			if ($this->id_usuario > 0) {
				$sql .= ' AND material.id_usuario = :id_usuario';
				$arrExecute = array_merge($arrExecute, array(':id_usuario' => $this->id_usuario));
			}
			
			$stm = $db->prepare($sql);			
			
			$stm->execute($arrExecute);
			
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}
	
	public function buscaMateriais() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						material.id,
						material.titulo,
						material.descricao,
						material.quantidade,
						material.situacao_anuncio,
						marca.descricao as marca,
						linha.descricao as linha,
						cor.descricao as cor,
						categoria.descricao as categoria,
						subcategoria.descricao as subcategoria
					FROM
						material
						LEFT JOIN marca ON (material.id_marca = marca.id_marca)
						LEFT JOIN linha ON (material.id_linha = linha.id_linha)
						LEFT JOIN cor ON (material.id_cor = cor.id_cor)
						LEFT JOIN categoria ON (material.id_categoria = categoria.id_categoria)
						LEFT JOIN categoria subcategoria ON (material.id_subcategoria = subcategoria.id_categoria)
					WHERE
						id_usuario = :id_usuario
					';

			$stm = $db->prepare($sql);
			$stm->execute(array(':id_usuario' => $this->id_usuario));
			
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}

	public function buscaAnuncioRecente() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						material.id,
						material.titulo,
						usuarios.nome_fantasia,
						cor.imagem as cor_imagem,
						cor.id_cor,
						cor.descricao as descricao_cor						
					FROM
						material
						INNER JOIN usuarios ON (material.id_usuario = usuarios.id)
						INNER JOIN cor ON (material.id_cor = cor.id_cor)
					WHERE
						situacao_anuncio = 1
					ORDER BY
						material.dt_criacao DESC
					LIMIT 4
					';
			$stm = $db->prepare($sql);
			$stm->execute();
			
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}
	
	public function buscaAnuncioPorCategoria() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						material.id,
						material.titulo,
						usuarios.nome_fantasia,
						cor.imagem as cor_imagem,
						cor.id_cor,
						cor.descricao as descricao_cor
					FROM
						material
						INNER JOIN usuarios ON (material.id_usuario = usuarios.id)
						INNER JOIN cor ON (material.id_cor = cor.id_cor)
					WHERE
						situacao_anuncio = 1
						AND (id_categoria = :id_categoria OR id_subcategoria = :id_subcategoria)
					ORDER BY
						material.dt_criacao DESC
					';
			$stm = $db->prepare($sql);
			
			$stm->execute(array(':id_categoria' => $this->id_categoria, ':id_subcategoria' => $this->id_subcategoria));
 // $stm->debugDumpParams();
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}	

	public function buscaAnuncioComFiltro($arrFiltro = array()) {
		try {
			$db = Conexao::getInstance();

			$filtro_where = '';
			$filtro_condicao = '';
			if ($this->id_categoria != null) {				
				$filtro_where .= $filtro_condicao . 'material.id_categoria = ' .$this->id_categoria;
				$filtro_condicao = ' AND ';
			}

			if ($this->id_subcategoria != null) {				
				$filtro_where .= $filtro_condicao . 'material.id_subcategoria = ' .$this->id_subcategoria;
				$filtro_condicao = ' AND ';
			}

			if ($this->id_marca != null) {				
				$filtro_where .= $filtro_condicao . 'material.id_marca = ' .$this->id_marca;
				$filtro_condicao = ' AND ';
			}

			if ($this->id_linha != null) {				
				$filtro_where .= $filtro_condicao . 'material.id_linha = ' .$this->id_linha;
				$filtro_condicao = ' AND ';
			}			

			if ($this->id_cor != null) {				
				$filtro_where .= $filtro_condicao . 'material.id_cor = ' .$this->id_cor;
				$filtro_condicao = ' AND ';
			}
			
			if (isset($arrFiltro['id_municipio']) && !isset($arrFiltro['limite_busca'])) {
				$filtro_where .= $filtro_condicao . 'usuarios.cidade = "' .$arrFiltro['id_municipio'].'"';
				$filtro_condicao = ' AND ';
			}			
			
			$incluir_consulta_distancia = '';
			$having_consulta_distancia = '';
			if (isset($arrFiltro['limite_busca'])) {
				$incluir_consulta_distancia = sprintf('
				, (
					6371 * acos(
						cos( radians(%s) )
						* cos( radians( municipios.latitude) )
						* cos( radians( municipios.longitude ) - radians(%s) )
						+ sin( radians(%1$s) )
						* sin( radians( municipios.latitude ) ) 
					)
				) AS distancia', $arrFiltro['latitude'], $arrFiltro['longitude']);
				
				$having_consulta_distancia = sprintf(' HAVING distancia <= %s', $arrFiltro['limite_busca']);
			}			

			if (isset($arrFiltro['palavra_chave'])) {
				$filtro_where .= sprintf(
					'%s ( UPPER(material.titulo) LIKE "%%%s%%") OR ' .
					' ( UPPER(material.descricao) LIKE "%%%2$s%%") OR ' .
					' ( UPPER(marca.descricao) LIKE "%%%2$s%%") OR ' .
					' ( UPPER(linha.descricao) LIKE "%%%2$s%%") OR ' .
					' ( UPPER(cor.descricao) LIKE "%%%2$s%%") ' ,
					$filtro_condicao,
					$arrFiltro['palavra_chave']
				);
				// $filtro_where .= $filtro_condicao . '( UPPER(material.titulo) LIKE "%'.strtoupper($arrFiltro['palavra_chave']).'%" OR UPPER(material.descricao) LIKE "%'.strtoupper($arrFiltro['palavra_chave']).'" )';
				$filtro_condicao = ' AND ';
 			}

			$filtro_where .= $filtro_condicao . 'material.situacao_anuncio = 1';

			$sql = 'SELECT
						material.id,
						material.titulo,
						material.descricao,
						usuarios.nome_fantasia,
						municipios.nome,
						cor.imagem as cor_imagem,
						cor.id_cor
					'
					.$incluir_consulta_distancia.	
					'
					FROM
						material
						INNER JOIN usuarios ON (material.id_usuario = usuarios.id)
						INNER JOIN municipios ON (usuarios.cidade = municipios.codigo_ibge)
						INNER JOIN marca ON (material.id_marca = marca.id_marca)
						INNER JOIN linha ON (material.id_linha = linha.id_linha)
						INNER JOIN cor ON (material.id_cor = cor.id_cor)
					WHERE
					'
					.
					$filtro_where
					.$having_consulta_distancia.
					'
					ORDER BY
						material.titulo DESC
					';
			$stm = $db->prepare($sql);
			// echo '<pre>'.$sql;exit;
			$stm->execute();
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}		

	public function buscaAnuncioPorAnunciante() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						material.id,
						material.titulo,
						usuarios.nome_fantasia
					FROM
						material
						INNER JOIN usuarios ON (material.id_usuario = usuarios.id)
					WHERE
						situacao_anuncio = 1
						AND (id_usuario = :id_usuario)
					ORDER BY
						material.dt_criacao DESC
					';
			$stm = $db->prepare($sql);
			
			$stm->execute(array(':id_usuario' => $this->id_usuario));
 // $stm->debugDumpParams();
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}		

	public function buscaMaterialImagem() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						id_material_imagem as handle,
						nome_arquivo
					FROM
						material_imagem
					WHERE
						id_material = :id
					';

			$stm = $db->prepare($sql);
			$stm->execute(array(':id' => $this->id));
			
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}	

	public function inserirImagem() {
		try {
			$db = Conexao::getInstance();

			$sql = 'INSERT INTO material_imagem (
						id_material,
						nome_arquivo						
					) VALUES 
					(
						:id_material,
						:nome_arquivo
					)';

			$stm = $db->prepare($sql);

            $stm->bindParam(':id_material',         $this->id);
            $stm->bindParam(':nome_arquivo',        $this->arquivo);
			
			$stm->execute();

		} catch (Exception $e) {
            $stm->debugDumpParams();
			print $e->getMessage();
		}
	}	

	public function excluirImagem() {
		try {
			$db = Conexao::getInstance();

			$sql = 'DELETE FROM 
						material_imagem
					WHERE
						id_material_imagem = :id_material_imagem';

			$stm = $db->prepare($sql);

            $stm->bindParam(':id_material_imagem',         $this->id_material_imagem);
			
			$stm->execute();

		} catch (Exception $e) {
            $stm->debugDumpParams();
			print $e->getMessage();
		}
	}	
	
	function dados() {
		return 		
			'{
				"titulo": {
					"obrigatorio":"true",
					"mensagem":"Informe o título"
				},
				"id_categoria": {
					"obrigatorio":"true",
					"mensagem":"Informe a categoria"
				},
				"id_subcategoria": {
					"obrigatorio":"true",
					"mensagem":"Preencha a subcategoria"
				},
				"id_marca": {
					"obrigatorio":"true",
					"mensagem":"Preencha a marca"
				},
				"id_linha": {
					"obrigatorio":"true",
					"mensagem":"Preencha a linha"
				},
				"id_cor": {
					"obrigatorio":"true",
					"mensagem":"Preencha a cor"
				}
			}';
	}
	
	public function buscaMaterialAutoComplete($palavra_chave) {
		try {
			$db = Conexao::getInstance();
					
			$sql = sprintf('
							SELECT 
								* 
							FROM (
									SELECT
									  material.id AS handle,
									  material.titulo AS descricao,
									  "Materiais" AS categoria
									FROM
									  material
									WHERE (
										UPPER (material.titulo) LIKE "%%%s%%"
										OR UPPER (material.descricao) LIKE "%%%1$s%%"
									  )
									  AND material.situacao_anuncio = 1
							
									UNION

									SELECT
									  marca.id_marca AS handle,
									  marca.descricao AS descricao,
									  "Marcas" AS categoria
									FROM
									  material
									  INNER JOIN marca ON (material.id_marca = marca.id_marca)
									WHERE (
										UPPER(marca.descricao) LIKE "%%%1$s%%"
									  )
									  AND material.situacao_anuncio = 1									

									UNION

									SELECT
									  linha.id_linha AS handle,
									  linha.descricao AS descricao,
									  "Linhas" AS categoria
									FROM
									  material
									  INNER JOIN linha ON (material.id_linha = linha.id_linha)
									WHERE (
										UPPER(linha.descricao) LIKE "%%%1$s%%"
									  )
									  AND material.situacao_anuncio = 1									
									
									UNION

									SELECT
									  cor.id_cor AS handle,
									  cor.descricao AS descricao,
									  "Cores" AS categoria
									FROM
									  material
									  INNER JOIN cor ON (material.id_cor = cor.id_cor)
									WHERE (
										UPPER(cor.descricao) LIKE "%%%1$s%%"
									  )
									  AND material.situacao_anuncio = 1
								) 
							AS consulta',
							$palavra_chave
					);
			$stm = $db->prepare($sql);
			$stm->execute();
 // $stm->debugDumpParams();
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}			
}
?>
