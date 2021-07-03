<?php

use App\Core\Model;

class Preco{
  public $id;
  public $primeiraHora;
  public $segundaHora;

  public function listarPrecos(){
    $sql = "SELECT * FROM tblPreco";
    $stmt = Model::conexaoDB()->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount() > 0){
      $resultado = $stmt->fetchAll(\PDO::FETCH_OBJ);
      return $resultado;
    }else{
      return [];
    }
  }

}