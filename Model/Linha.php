<?php
namespace SuaMadeira\Model;

use SuaMadeira\Model\Conexao;
use PDO;

class Linha {

	public $id_linha;
	public $descricao;
	public $situacao;
	public $id_marca;
	public $dt_criacao;
	public $dt_modificado;

	public function listar() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						id_linha,
						descricao
					FROM
						linha
					WHERE
						situacao = :situacao
					';
					

			$stm = $db->prepare($sql);
			
			$stm->execute(array(':situacao' => $this->situacao));
			
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}

	public function listarLinha() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						id_marca,
						id_linha,
						descricao
					FROM
						linha
					WHERE
						id_linha = :id_linha
					ORDER BY
						descricao ASC
					';
					

			$stm = $db->prepare($sql);
			
			$stm->execute(array(':id_linha' => $this->id_linha));
			
			return $stm->fetch(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}	

	public function inserir() {
		try {
			$db = Conexao::getInstance();

			$sql = 'INSERT INTO linha (
						descricao,
						situacao,
						id_marca,
						dt_criacao,
						dt_modificado
					) VALUES 
					(
						:descricao,
						:situacao,
						:id_marca,
						:dt_criacao,
						:dt_modificado
					)';

			$stm = $db->prepare($sql);

			$stm->bindParam(':descricao', $this->descricao);
			$stm->bindParam(':situacao', $this->situacao);
			$stm->bindParam(':id_marca', $this->id_marca);
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
						linha 
					SET
						descricao=:descricao,
						situacao=:situacao,
						id_marca:=:id_marca,
						dt_modificado=:dt_modificado
					WHERE
						id_linha=:id_linha';

			$stm = $db->prepare($sql);

            $data = array(
				'descricao' => $this->descricao,
				'situacao' => $this->situacao,
				'dt_modificado' => $this->dt_modificado,
				'id_marca' => $this->id_marca,
				'id_linha' => $this->id_linha
			);

			$stm->execute($data);
			return true;
		} catch (Exception $e) {
            $stm->debugDumpParams();
			print $e->getMessage();
		}
	}	

	public function listarPorMarca() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						id_linha,
						descricao
					FROM
						linha
					WHERE
						situacao = :situacao
						AND id_marca = :id_marca
					ORDER BY
						descricao ASC						
					';
					
			$stm = $db->prepare($sql);
			$stm->execute(array(':situacao' => $this->situacao, ':id_marca' => $this->id_marca));
			
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}	
}
?>
