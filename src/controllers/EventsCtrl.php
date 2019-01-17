<?php
namespace App\controllers;
use App\models\EventsOperations;

class EventsCtrl extends BaseController
{

  public function addEvents($request, $response, $args)
  {
    $eventsOperations = new EventsOperations();
    $datas = $request->getParsedBody();
    // print_r($datas);
    // die();
    $eventsOperations->addEvents($datas);
    // return $this->c->view->render($response, 'sample.twig');
  }

}

 ?>
