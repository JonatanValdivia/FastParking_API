<?php

use App\Core\Controller;

class Cadastros extends Controller{
  
  public function index(){
    $cadastroModel = $this->model("Cadastro");

    $dados = $cadastroModel->listarTodos();

    echo "<pre>" . json_encode($dados, JSON_UNESCAPED_UNICODE) . "</pre>";
  }
}