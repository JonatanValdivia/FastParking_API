<?php

use App\Core\Controller;
use App\Core\Model;

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
      http_response_code(201);
      echo json_encode($precoModel);
    }else{
      http_response_code(500);
      $erro = ["erro" => "Problemas ao inserir novo preco"];
      echo json_encode($erro);
    }

  }
}