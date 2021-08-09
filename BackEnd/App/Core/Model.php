<?php

namespace App\Core;

class Model{
  private static $conexao;

  public static function conexaoDB(){
    $host = $_ENV["database_host"];
    $user = $_ENV["database_user"];
    $pass = $_ENV["database_pass"];
    
      
    
    if(!isset(self::$conexao)){
      self::$conexao = new \PDO("mysql:host=$host;post=3306;dbname=FastParking;", $user, $pass); 
    }

    return self::$conexao;
  }
}