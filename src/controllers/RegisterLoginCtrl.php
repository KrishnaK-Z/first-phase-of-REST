<?php
namespace App\controllers;

use App\models\RegisterOperations;
use App\models\LoginOperations;

class RegisterLoginCtrl extends BaseController
{


  //Registration Controllers
  public function registerSubmitController ($request, $response, $args) {
      $registerOperations = new RegisterOperations();
      $datas = $request->getParsedBody();
      // print_r($datas);

      $uploadedFiles = $request->getUploadedFiles();
      $uploadedFile = $uploadedFiles['profile_pic'];
      $datas['uploadedProfilePic'] = $uploadedFile;
      $registerOperations->registerUser($datas);
      return $this->c->view->render($response, 'sample.twig');
  }

  public function registerController( $request,  $response,  $args) {
    $this->c->view->render($response,'register.twig');
  }

  //Login Controllers
  public function loginHome( $request,  $response,  $args) {
    $this->c->view->render($response,'login.twig');
  }

  public function loginSuccess ( $request,  $response,  $args) {
    $loginOperations = new LoginOperations();
    $datas = $request->getParsedBody();
    $result = $loginOperations->loginUser($datas);
    // echo $_SESSION['user_id'];
    return $this->c->view->render($response, 'sample.twig');
  }


  //Show All User Details
  public function showAllUserDetailsCtrl ( $request,  $response, $args) {
    $registerOperations = new RegisterOperations();
    $results = $registerOperations->showAllUserDetails(); //returns an array
    // print_r($results);
    return $this->c->view->render($response, 'usertable.twig', compact('results'));
  }



  //Sample
  public function index($request,$response)
  {
    return $this->c->view->render($response,'sample.twig');
  }

}
 ?>
