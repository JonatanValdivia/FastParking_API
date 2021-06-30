<?php

use App\Core\Model;

class Cliente{
  private $id;
  private $nome;
  private $placa;
  private $dataHoraEstacionado;

  public function listarTodos(){
    $sql = " SELECT id, nome, placa, date_format(dataHoraEstacionado, '%d/%m/%Y')as dataEstacionado,
    time_format(dataHoraEstacionado, '%H:%i') as hora from tblCliente;";
    $stmt = Model::conexaoDB()->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount()>0){
      $resultado = $stmt->fetchAll(\PDO::FETCH_OBJ);
      return $resultado;
    }else{
      return [];
    }
  }

  public function buscarPorId($id){
    $sql = "SELECT * from tblCliente where id = ?;";
    $stmt = Model::conexaoDB()->prepare($sql);
    $stmt->bindValue(1, $id);
    if($stmt->execute()){
      $cliente = $stmt->fetch(PDO::FETCH_OBJ);

      if(!$cliente){
        return false;
      }

      $this->id = $cliente->id;
      $this->nome = $cliente->nome;
      $this->placa = $cliente->placa;
      $this->dataHoraEstacionado = $cliente->dataHoraEstacionado;

      return $cliente;
    }else{
      return false;
    }
  }

}