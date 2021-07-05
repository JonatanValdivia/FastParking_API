<?php

use App\Core\Controller;

class Precos extends Controller{

  public function index(){
    $precoModel = $this->model("Preco");
    $dados = $precoModel->listarPrecos();
    echo json_encode($dados, JSON_UNESCAPED_UNICODE);
  }

  public function store(){
    $json = file_get_contents("php://input");
    $novoPreco = json_decode($json);
    $precoModel = $this->model("Preco");
    $precoModel->primeiraHora = $novoPreco->primeiraHora;
    $precoModel->segundaHora = $novoPreco->segundaHora;
    if($precoModel->inserir()){
      return http_response_code(201);
    }else{
      return http_response_code(500);
    }
  }
}