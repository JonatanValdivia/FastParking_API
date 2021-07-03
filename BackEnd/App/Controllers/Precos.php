<?php

use App\Core\Controller;
use App\Core\Model;

class Precos extends Controller{

  public function index(){
    $precoModel = $this->model("Preco");
    $dados = $precoModel->listarPrecos();
    echo json_encode($dados, JSON_UNESCAPED_UNICODE);
  }

}