<?php

use App\Core\Model;

class Cadastro extends Model{
  private $id;
  private $nome;
  private $placa;
  private $dataEstacionado;
  private $hora;
  public function listarTodos(){
    $sql = " SELECT id, nome, placa, date_format(dataEstacionado, '%d/%m/%Y')as dataEstacionado, time_format(hora, '%H:%i') as hora from tblCadastro; ";
    $stmt = Model::conexaoDB()->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount()>0){
      $resultado = $stmt->fetchAll(\PDO::FETCH_OBJ);
      return $resultado;
    }else{
      return [];
    }
  }
}