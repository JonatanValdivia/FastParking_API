<?php

namespace App\Core;

class Model{
  private static $conexao;

  public static function conexaoDB(){
    if(!isset(self::$conexao)){
      self::$conexao = new \PDO("mysql:host=localhost;post=3306;dbname=FastParking;", "root", "bcd127"); 
    }

    return self::$conexao;
  }
}