<?php
namespace SuaMadeira\Model;

use SuaMadeira\Model\Conexao;
use PDO;

class Espessura	 {

	public $id_espessura;
	public $valor;
	public $situacao;
	public $dt_criacao;
	public $dt_modificado;

	public function listarEspessuras() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						id_espessura,
						valor
					FROM
						espessura
					WHERE
						situacao = :situacao
					ORDER BY
						valor ASC
					';
					

			$stm = $db->prepare($sql);
			
			$stm->execute(array(':situacao' => $this->situacao));
			
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}
	
	public function buscaEspessura() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						*
					FROM
						espessura
					WHERE
						id_espessura = :id_espessura
					';
					

			$stm = $db->prepare($sql);
			
			$stm->execute(array(':id_espessura' => $this->id_espessura));
				
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}	
	
	public function listarTodasEspessuras() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						id_espessura,
						valor,
						(SELECT count(material.id) FROM material WHERE id_espessura = espessura.id_espessura and situacao_anuncio = 1) as qtdAnuncio
					FROM
						espessura
					ORDER BY
						valor ASC
					';
					

			$stm = $db->prepare($sql);
			
			$stm->execute();
			
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}

	public function listarTodasEspessurasTotal() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						count(id_espessura) as total
					FROM
						espessura
					ORDER BY
						valor
					';
					

			$stm = $db->prepare($sql);
			
			$stm->execute();
			
			return $stm->fetch(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}
	
	public function inserir() {
		try {
			$db = Conexao::getInstance();

			$sql = 'INSERT INTO espessura (
						valor,
						situacao,
						dt_criacao,
						dt_modificado
					) VALUES 
					(
						:valor,
						:situacao,
						:dt_criacao,
						:dt_modificado
					)';

			$stm = $db->prepare($sql);

			$stm->bindParam(':valor', $this->valor);
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
						espessura 
					SET
						valor=:valor,
						situacao=:situacao,
						dt_modificado=:dt_modificado
					WHERE
						id_espessura=:id_espessura';

			$stm = $db->prepare($sql);

            $data = array(
				'valor' => $this->valor,
				'situacao' => $this->situacao,
				'dt_modificado' => $this->dt_modificado,
				'id_espessura' => $this->id_espessura
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
