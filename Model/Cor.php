<?php
namespace SuaMadeira\Model;

use SuaMadeira\Model\Conexao;
use PDO;

class Cor {

	public $id_cor;
	public $descricao;
	public $situacao;
	public $id_linha;
	public $dt_criacao;
	public $dt_modificado;
	public $imagem;

	public function listar() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						id_cor,
						descricao,
						imagem
					FROM
						cor
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

	public function listarCor() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						cor.id_cor,
						cor.id_linha,
						marca.id_marca,
						cor.descricao,
						cor.imagem
					FROM
						cor
						INNER JOIN linha ON (linha.id_linha = cor.id_linha)
						INNER JOIN marca ON (marca.id_marca = linha.id_marca)
					WHERE
						id_cor = :id_cor
					';
					

			$stm = $db->prepare($sql);
			
			$stm->execute(array(':id_cor' => $this->id_cor));
			
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}	

	public function inserir() {
		try {
			$db = Conexao::getInstance();

			$sql = 'INSERT INTO cor (
						descricao,
						situacao,
						id_linha,
						dt_criacao,
						dt_modificado,
						imagem
					) VALUES 
					(
						:descricao,
						:situacao,
						:id_linha,
						:dt_criacao,
						:dt_modificado,
						:imagem
					)';

			$stm = $db->prepare($sql);

			$stm->bindParam(':descricao', $this->descricao);
			$stm->bindParam(':situacao', $this->situacao);
			$stm->bindParam(':id_linha', $this->id_linha);
			$stm->bindParam(':dt_criacao', $this->dt_criacao);
			$stm->bindParam(':dt_modificado', $this->dt_modificado);
			$stm->bindParam(':imagem', $this->imagem);
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

			$sql_imagem = '';
			$arrImagem = [];
			if ($this->imagem != NULL) {
				$sql_imagem = ', imagem=:imagem';
				$arrImagem['imagem'] = $this->imagem;
			}

			$sql = 'UPDATE
						cor 
					SET
						descricao=:descricao,
						situacao=:situacao,
						id_linha:=:id_linha,
						dt_modificado=:dt_modificado
					'
					. $sql_imagem.
					'
					WHERE
						id_cor=:id_cor';

			$stm = $db->prepare($sql);

            $data = array(
				'descricao' => $this->descricao,
				'situacao' => $this->situacao,
				'dt_modificado' => $this->dt_modificado,
				'id_linha' => $this->id_linha,
				'id_cor' => $this->id_cor
			);

			$data = array_merge($data, $arrImagem);

			$stm->execute($data);
			return true;
		} catch (Exception $e) {
            $stm->debugDumpParams();
			print $e->getMessage();
		}
	}		

	public function listarPorLinha() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						id_cor,
						descricao,
						imagem
					FROM
						cor
					WHERE
						situacao = :situacao
						AND id_linha = :id_linha
					ORDER BY
						descricao ASC
					';
					

			$stm = $db->prepare($sql);
			
			$stm->execute(array(':situacao' => $this->situacao, ':id_linha' => $this->id_linha));
			
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}

	public function atualizarImagem() {
		try {
			$db = Conexao::getInstance();

			$sql = 'UPDATE
						cor 
					SET
						imagem=:imagem
					WHERE
						id_cor=:id_cor';

			$stm = $db->prepare($sql);

            $data = array(
				'imagem' => $this->imagem
			);

			$data = array_merge($data, array('id_cor' => $this->id_cor));
			
			return $stm->execute($data);
			
			
		} catch (Exception $e) {
            $stm->debugDumpParams();
			print $e->getMessage();
		}
	}	
}
?>
