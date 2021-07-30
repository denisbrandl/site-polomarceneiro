<?php
namespace SuaMadeira\Model;

use SuaMadeira\Model\Conexao;
use PDO;

class Categoria	 {

	public $id_categoria;
	public $id_categoria_pai;
	public $descricao;
	public $situacao;
	public $dt_criacao;
	public $dt_modificado;

	public function listarCategorias() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						id_categoria,
						descricao
					FROM
						categoria
					WHERE
						situacao = :situacao
						AND id_categoria_pai = :id_categoria_pai
					';
					

			$stm = $db->prepare($sql);
			
			$stm->execute(array(':situacao' => $this->situacao, ':id_categoria_pai' => $this->id_categoria_pai));
			
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}
	
	public function buscaCategoria() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						*
					FROM
						categoria
					WHERE
						id_categoria = :id_categoria
					';
					

			$stm = $db->prepare($sql);
			
			$stm->execute(array(':id_categoria' => $this->id_categoria));
				
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}	
	
	public function listarTodasCategorias() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						id_categoria,
						descricao,
						id_categoria_pai,
						(SELECT count(material.id) FROM material WHERE id_subcategoria = categoria.id_categoria  and situacao_anuncio = 1) as qtdAnuncio
					FROM
						categoria
					WHERE
						situacao = :situacao
					ORDER BY
						descricao,
						id_categoria_pai
					';
					

			$stm = $db->prepare($sql);
			
			$stm->execute(array(':situacao' => $this->situacao));
			
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}

	public function listarTodasCategoriasTotal() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						count(id_categoria) as total
					FROM
						categoria
					WHERE
						situacao = :situacao
					ORDER BY
						descricao,
						id_categoria_pai
					';
					

			$stm = $db->prepare($sql);
			
			$stm->execute(array(':situacao' => $this->situacao));
			
			return $stm->fetch(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}	

	public function listarPorLinha() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						id_cor,
						descricao
					FROM
						cor
					WHERE
						situacao = :situacao
						AND id_linha = :id_linha
					';
					

			$stm = $db->prepare($sql);
			
			$stm->execute(array(':situacao' => $this->situacao, ':id_linha' => $this->id_linha));
			
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}
	
	public function inserir() {
		try {
			$db = Conexao::getInstance();

			$sql = 'INSERT INTO categoria (
						descricao,
						situacao,
						id_categoria_pai,
						dt_criacao,
						dt_modificado
					) VALUES 
					(
						:descricao,
						:situacao,
						:id_categoria_pai,
						:dt_criacao,
						:dt_modificado
					)';

			$stm = $db->prepare($sql);

			$stm->bindParam(':descricao', $this->descricao);
			$stm->bindParam(':situacao', $this->situacao);
			$stm->bindParam(':id_categoria_pai', $this->id_categoria_pai);
			$stm->bindParam(':dt_criacao', $this->dt_criacao);
			$stm->bindParam(':dt_modificado', $this->dt_modificado);
			$stm->execute();
			
			return $db->lastInsertId();

		} catch (Exception $e) {
			$stm->debugDumpParams();
			die($e->getMessage());
		}
	}

	public function editar() {
		try {
			$db = Conexao::getInstance();

			$sql = 'UPDATE
						categoria 
					SET
						descricao=:descricao,
						situacao=:situacao,
						id_categoria_pai:=:id_categoria_pai,
						dt_modificado=:dt_modificado
					WHERE
						id_categoria=:id_categoria';

			$stm = $db->prepare($sql);

            $data = array(
				'descricao' => $this->descricao,
				'situacao' => $this->situacao,
				'dt_modificado' => $this->dt_modificado,
				'id_categoria_pai' => $this->id_categoria_pai,
				'id_categoria' => $this->id_categoria
			);

			$stm->execute($data);
			return true;
		} catch (Exception $e) {
            $stm->debugDumpParams();
			print $e->getMessage();
		}
	}	
}
?>
