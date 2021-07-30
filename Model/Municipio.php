<?php
namespace SuaMadeira\Model;

use SuaMadeira\Model\Conexao;
use PDO;

class Municipio {

	public $codigo_ibge;
	public $nome;
	public $latitude;
	public $longitude;
	public $capital;
	public $codigo_uf;

	public function listarPorEstado() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						codigo_ibge,
						nome,
						latitude,
						longitude,
						capital,
						codigo_uf
					FROM
						municipios
					WHERE
						codigo_uf = :codigo_uf
					ORDER BY
						nome ASC
					';
					

			$stm = $db->prepare($sql);
			
			$stm->execute(array(':codigo_uf' => $this->codigo_uf));
			
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}
	
	public function consultaMunicipio() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						codigo_ibge,
						nome,
						latitude,
						longitude,
						capital,
						codigo_uf
					FROM
						municipios
					WHERE
						codigo_ibge = :codigo_ibge
					';
					

			$stm = $db->prepare($sql);
			
			$stm->execute(array(':codigo_ibge' => $this->codigo_ibge));
			
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}	
	
	public function listarMunicipiosComAnuncio() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						mu.nome,
						mu.codigo_ibge,
						es.uf
					FROM
						municipios mu
					INNER JOIN usuarios us ON (us.cidade = mu.codigo_ibge)
					INNER JOIN estados es ON (es.codigo_uf = mu.codigo_uf)
					INNER JOIN material ma ON (ma.id_usuario = us.id AND ma.situacao_anuncio = 1)
					GROUP BY
						mu.codigo_ibge
					ORDER BY
						es.uf ASC, mu.nome ASC
					';
					

			$stm = $db->prepare($sql);
			
			$stm->execute();
			
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}	
}
?>
