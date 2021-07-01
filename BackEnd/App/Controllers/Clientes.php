<?php

use App\Core\Controller;

class Clientes extends Controller{
  
  public function index(){
    $cadastroModel = $this->model("Cliente");

    $dados = $cadastroModel->listarTodos();

    echo "<pre>" . json_encode($dados, JSON_UNESCAPED_UNICODE) . "</pre>";
  }

  public function find($id){
    $clienteModel = $this->model("Cliente");
    $clienteModel = $clienteModel->buscarPorId($id);

    if($clienteModel){
      echo json_encode($clienteModel, JSON_UNESCAPED_UNICODE);
    }else{
      http_response_code(404);
      $erro = ["erro" => "Cliente não encontrado"];
      echo json_encode($erro, JSON_UNESCAPED_UNICODE);
    }
  }

  public function store(){
    $json = file_get_contents("php://input");
    
    $novoCliente = json_decode($json);
    $clienteModel = $this->model("Cliente");
    $clienteModel->nome = $novoCliente->nome;
    $clienteModel->placa = $novoCliente->placa;
    $clienteModel = $clienteModel->inserir();

    if($clienteModel){
      http_response_code(201);
      echo json_encode($clienteModel);
    }else{
      http_response_code(500);
      $erro = ["erro" => "Problemas ao inserir novo cliente"];
      echo json_encode($erro);
    }
  }

  public function update($id){
    $json = file_get_contents("php://input");

    $editarCliente = json_decode($json);
    $clienteModel = $this->model("Cliente");
    $clienteModel = $clienteModel->buscarPorId($id);

    if(!$clienteModel){
      http_response_code(404);
      $erro = ["erro" => "Cliente não encontrado"];
      echo json_encode($erro);
      exit;
    }

    $clienteModel->nome = $editarCliente->nome;
    $clienteModel->placa = $editarCliente->placa;

    if($clienteModel->atualizar()){
      http_response_code(204);  
    }else{
      http_response_code(500);
      $erro = ["erro" => "Problemas ao editar o cliente"];
      echo json_encode($erro, JSON_UNESCAPED_UNICODE);
    }
  }

  public function delete($id){
    $clienteModel = $this->model("Cliente");
    $clienteModel = $clienteModel->buscarPorId($id);
    if(!$clienteModel){
      http_response_code(404);
      $erro = ["erro" => "Cliente não encontrado"];
      echo json_encode($erro);  
    }
    if($clienteModel->deletar()){
      http_response_code(204);
    }else{
      http_response_code(500);
      $erro = ["erro" => "Problemas ao deletar cliente"];
      echo json_encode($erro);
    }
    $clienteModel = $clienteModel->deletar();
  }
}