<?php

use App\Core\Controller;

class ClientesPrecos extends Controller{
  public function index(){ 
    $cadastroModel = $this->model("Cliente");
    $dados = $cadastroModel->listarTodos();
    echo json_encode($dados, JSON_UNESCAPED_UNICODE);
  }

  public function store(){
    $clienteModel = $this->model('Cliente');
    $precoModel = $this->model('Preco');
    $precoStore = $precoModel->store();
    $clienteStore = $clienteModel->store();

    if ($precoStore != http_response_code(500)) :
      $retPreco = json_encode($precoStore);
    else :
      $retPreco = 'erro';
    endif;

    if ($clienteStore != http_response_code(500)) :
      $retCliente = json_encode($clienteStore);
    else :
      $retCliente = 'erro';
    endif;


    return [$retPreco, $retCliente];
  }
}
