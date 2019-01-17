<?php

namespace App\controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Interop\Container\ContainerInterface;

abstract class BaseController
{
  protected $c;

  public function __construct(ContainerInterface $c)
  {
    $this->c = $c;
  }
}
 ?>
