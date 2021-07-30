<?php

namespace SuaMadeira\Model;

use PDO;
use Dotenv;

class Conexao
{

  /*  
    * Atributo estático para instância do PDO  
    */
  protected static $instance;

  /*  
    * Escondendo o construtor da classe  
  */
  private function __construct()
  { }

  public static function getInstance()
  {
	  
	$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1).'/');
	$dotenv->load();
    if (empty(self::$instance)) {

      $db_info = array(
		"db_host" => $_ENV['DB_HOST'],
        "db_port" => "3306",
        "db_user" => $_ENV['DB_USUARIO'],
        "db_pass" => $_ENV['DB_SENHA'],
        "db_name" => $_ENV['DB_BASE'],
        "db_charset" => "UTF-8"
      );

      try {
        self::$instance = new PDO("mysql:host=" . $db_info['db_host'] . ';port=' . $db_info['db_port'] . ';dbname=' . $db_info['db_name'], $db_info['db_user'], $db_info['db_pass']);
        self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$instance->query('SET NAMES utf8');
        self::$instance->query('SET CHARACTER SET utf8');
      } catch (PDOException $error) {
        echo $error->getMessage();
      }
    }

    return self::$instance;
  }
}
