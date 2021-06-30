<?php
namespace App\Core;

class Router{
  private $controller;
  private $method;
  private $params = [];
  private $controllerMethod;

  function __construct(){
    //Serve para liberar as origens a acessar a nossa aplicação
    header('Access-Control-Allow-Origin: *');
    //serve para habilitar os métodos que serão usados. Habilitamos o opticons para ser realizado o preflight. Uma requisição de confirmação
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    //Os headers que serão usados
    header("Access-Control-Allow-Headers: Content-Type");
    //setamos que a resposta será enviada no formato JSON
    header("content-tyle: application/json");
    //Verificando se o controller passado na url (mais especificamente na primeira posição), existe dentro de App/Controllers/"arquivo". 
    $url = $this->parseUrl();
    if(file_exists("../App/Controllers/" . $url[1] . ".php")){
      //Se esse arquivo realmente existir, passamos-o para a variável/atributo controller:
      $this->controller = $url[1];
      //Como já a utilizamos, não necessitamos mais, então damos um unset:
      unset($url[1]);
    }elseif(empty($url[1])){

    }else{//Se não existir esse controller passado na URL, faz-se:
      //$this->controller = "cadastros";
    }
    //Se o primeiro if for true, então damos um require_once, concatenado com o controller
    require_once "../App/Controllers/" . $this->controller . ".php";

    $this->controller = new $this->controller;

    $this->method = $_SERVER["REQUEST_METHOD"];
    //Pegando o método, de acordo com a requisição
    switch($this->method){
      case "GET":

        if(isset($url[2])){
          $this->controllerMethod = "find";
          $this->params = [$url[2]];
        }else{
          $this->controllerMethod = "index";
        }
        
        break;

      case "POST":
        $this->controllerMethod = "store";
        break;
      case "PUT":
        $this->controllerMethod = "update";
        if(isset($url[2]) && is_numeric($url[2])){
          $this->params = [$url[2]];
        }else{
          http_response_code(400);
          json_encode(["erro" => "É necessário informar o id"]);
          exit;
        }
        break;
      case "DELETE":
        $this->controllerMethod = "delete";
        if(isset($url[2]) && is_numeric($url[2])){
          $this->params = [$url[2]];
        }else{
          http_response_code(400);
          echo json_encode(["Erro" => "É necessário informar o id"]);
          exit;
        }
        break;

        default:
          echo "Método não suportado";
          exit;
          break;
    }
    //$this->params = $url ? array_values($url) : [];
    call_user_func_array([$this->controller, $this->controllerMethod], $this->params);
  }

  
  private function parseUrl(){
    //A barra no começo é o tipo de separador
    return explode("/", $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
  }

}
