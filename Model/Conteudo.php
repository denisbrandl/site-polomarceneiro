<?php
namespace SuaMadeira\Model;

use SuaMadeira\Model\Conexao;
use PDO;

class Conteudo {

	public $id_conteudo;
	public $titulo;
	public $descricao;
	public $dt_criacao;
	public $dt_modificado;
	
	public function consultaConteudo() {
		try {
			$db = Conexao::getInstance();

			$sql = 'SELECT
						id_conteudo,
						titulo,
						descricao,
						dt_criacao,
						dt_modificado
					FROM
						conteudo
					WHERE
						id_conteudo = :id_conteudo
					';
					

			$stm = $db->prepare($sql);
			
			$stm->execute(array(':id_conteudo' => $this->id_conteudo));
			
			return $stm->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			print $e->getMessage();
		}	
	}
}
?>
