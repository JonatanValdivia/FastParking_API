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

  public function inserir(){
    $sql = " INSERT into tblPreco (primeiraHora, segundaHora) values (?, ?); ";
    $stmt = Model::conexaoDB()->prepare($sql);
    $stmt->bindValue(1, $this->primeiraHora);
    $stmt->bindValue(2, $this->segundaHora);
    
    if($stmt->execute()){
      $this->id = Model::conexaoDB()->lastInsertId();
      return $this;
    }else{
      return false;
    }


  }

}