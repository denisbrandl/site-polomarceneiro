<?php
namespace SuaMadeira\Model;

use SuaMadeira\Model\Conexao;
use PDO;

class Marca {

	public $id_marca;
	public $descricao;
	public $situacao;
	public $dt_criacao;
	public $dt_modificado;

	public function inserir() {
		try {
			$db = Conexao::getInstance();

			$sql = 'INSERT INTO marca (
						descricao,
						situacao,
						dt_criacao,
						dt_modificado
					) VALUES 
					(
						:descricao,
						:situacao,
						:dt_criacao,
						:dt_modificado
					)';

			$stm = $db->prepare($sql);

			$stm->bindParam(':descricao', $this->descricao);
			$stm->bindParam(':situacao', $this->situacao);
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
						marca 
					SET
						descricao=:descricao,
						situacao=:situacao,
						dt_modificado=:dt_modificado
					WHERE
						id_marca=:id_marca';

			$stm = $db->prepare($sql);

            $data = array(
				'descricao' => $this->descricao,
				'situacao' => $this->situacao,
				'dt_modificado' => $this->dt_modificado,
				'id_marca' => $this->id_marca
			);

			$stm->execute($data);
			return true;
		} catch (Exception $e) {
            $stm->debugDumpParams();
			print $e->getMessage();
		}
	}		

	public function listar() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						id_marca,
						descricao
					FROM
						marca
					WHERE
						situacao = :situacao
					ORDER BY
						descricao ASC
					';
					

			$stm = $db->prepare($sql);
			
			$stm->execute(array(':situacao' => $this->situacao));
			
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}
	
	public function listarHierarquia($parametros) {
		try {
			$db = Conexao::getInstance();

			$where = '';
			if (!empty($parametros['search'])) {
				$where = sprintf(
							' WHERE (
										UPPER(marca.descricao) LIKE "%s%%"
										OR 
										UPPER(linha.descricao) LIKE "%1$s%%"
										OR 
										UPPER(cor.descricao) LIKE "%1$s%%"
							)',
							strtoupper($parametros['search'])
				);
			}
			
			$sql = '
					SELECT
					   marca.id_marca,
					   marca.descricao AS marca,
					   linha.id_linha,
					   linha.descricao AS linha,
					   cor.id_cor,
					   cor.descricao AS cor
					FROM
					   marca
					   LEFT JOIN linha ON (marca.id_marca = linha.id_marca)
					   LEFT JOIN cor ON (linha.id_linha = cor.id_linha)
					'.$where.'
					ORDER BY
					  marca.descricao ASC, linha.descricao ASC, cor.descricao ASC
					LIMIT '.$parametros['length'].' OFFSET '.$parametros['start'];
			
			$stm = $db->prepare($sql);
			
			$stm->execute();
			
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}	
	
	public function totalHierarquia($parametros) {
		try {
			$db = Conexao::getInstance();

			$where = '';
			if (!empty($parametros['search'])) {
				$where = sprintf(
							' WHERE (
										UPPER(marca.descricao) LIKE "%s%%"
										OR 
										UPPER(linha.descricao) LIKE "%1$s%%"
										OR 
										UPPER(cor.descricao) LIKE "%1$s%%"
							)',
							strtoupper($parametros['search'])
				);
			}

			$sql = '
					SELECT
					   count(id_cor) AS total
					FROM
					   marca
					   LEFT JOIN linha ON (marca.id_marca = linha.id_marca)
					   LEFT JOIN cor ON (linha.id_linha = cor.id_linha)
					'.$where;
			$stm = $db->prepare($sql);
			
			$stm->execute();
			
			return $stm->fetch(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}	

	public function listarMarca() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						id_marca,
						descricao
					FROM
						marca
					WHERE
						id_marca = :id_marca
					ORDER BY
						descricao ASC
					';
					

			$stm = $db->prepare($sql);
			
			$stm->execute(array(':id_marca' => $this->id_marca));
			
			return $stm->fetch(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}	
}
?>
