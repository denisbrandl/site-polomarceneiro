<?php
namespace SuaMadeira\Model;

use SuaMadeira\Model\Conexao;
use PDO;

class Estado {

	public $codigo_uf;
	public $uf;
	public $nome;
	public $latitude;
	public $longitude;

	public function listar() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						codigo_uf,
						uf,
						nome,
						latitude,
						longitude
					FROM
						estados
					ORDER BY
						nome ASC
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
