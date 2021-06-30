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
}