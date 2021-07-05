<?php

use App\Core\Model;

class Cliente
{
  public $id;
  public $nome;
  public $placa;
  public $dataHoraEstacionado;

  public function listarTodos(){
    $sql = "SELECT tblcliente.id, 
    nome, placa, 
    date_format(dataHoraEstacionado, '%d/%m/%Y')as dataEstacionado,
    time_format(dataHoraEstacionado, '%H:%i') as hora,
    tblpreco.primeiraHora, tblpreco.segundaHora from tblcliente inner join tblpreco on
    (tblpreco.id = tblcliente.idPreco);";
    $stmt = Model::conexaoDB()->prepare($sql);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
      $resultado = $stmt->fetchAll(\PDO::FETCH_OBJ);
      return $resultado;
    } else {
      return [];
    }
  }

  public function buscarPorId($id){
    $sql = "SELECT * from tblCliente where id = ?;";
    $stmt = Model::conexaoDB()->prepare($sql);
    $stmt->bindValue(1, $id);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
      $cliente = $stmt->fetch(PDO::FETCH_OBJ);
      $this->id = $cliente->id;
      $this->nome = $cliente->nome;
      $this->placa = $cliente->placa;
      $this->dataHoraEstacionado = $cliente->dataHoraEstacionado;

      return $this;
    } else {
      return false;
    }
  }

  public function inserir(){
    $sql = "SELECT id from tblpreco order by 1 desc limit 1";
    $stmt = Model::conexaoDB()->prepare($sql);
    if ($stmt->execute()) {
      $id = $stmt->fetch(PDO::FETCH_ASSOC);
      $this->idPreco = $id['id'];
      $sql = "INSERT into tblCliente (nome, placa, idPreco) values (?, ?, ?)";
      $stmt = Model::conexaoDB()->prepare($sql);
      $stmt->bindValue(1, $this->nome);
      $stmt->bindValue(2, $this->placa);
      $stmt->bindValue(3, $this->idPreco);
      if ($stmt->execute()) {
        $this->id = Model::conexaoDB()->lastInsertId();
        return $this;
      } else {
        return false;
      }
    }

    $sql = "INSERT into tblCliente (nome, placa) values (?, ?)";
    $stmt = Model::conexaoDB()->prepare($sql);
    $stmt->bindValue(1, $this->nome);
    $stmt->bindValue(2, $this->placa);
    if ($stmt->execute()) {
      $this->id = Model::conexaoDB()->lastInsertId();
      return $this;
    } else {
      return false;
    }
  }

  public function atualizar(){
    $sql = "UPDATE tblCliente SET nome = ?, placa = ? WHERE id = ?";

    $stmt = Model::conexaoDB()->prepare($sql);
    $stmt->bindValue(1, $this->nome);
    $stmt->bindValue(2, $this->placa);
    $stmt->bindValue(3, $this->id);

    return $stmt->execute();
  }

  public function deletar(){
    $sql = "DELETE from tblCliente where id = ?";

    $stmt = Model::conexaoDB()->prepare($sql);
    $stmt->bindValue(1, $this->id);
    return $stmt->execute();
  }
}
