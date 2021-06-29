<?php
namespace App\Core;

class Router{
  private $controller;
  private $method;
  private $params = [];
  private $controllerMethod;
  
  private function parseUrl(){
    //A barra no começo é o tipo de separador
    return explode("/", $_SERVER["SERVER_NAME"], $_SERVER["REQUEST_URI"]);
  }
}
