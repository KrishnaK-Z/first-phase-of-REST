<?php

use App\controllers\RegisterLoginCtrl;
use App\controllers\EventsCtrl;

$app->get('/', RegisterLoginCtrl::class . ':index');



//email showuser?email="krishsna@gmail.com"

$app->group('/register',function(){
  $this->get('', RegisterLoginCtrl::class . ':registerController');
  $this->post('', RegisterLoginCtrl::class . ':registerSubmitController')->setName('register_form_action');
});



//sample
$app->get('/showuser', RegisterLoginCtrl::class . ':showAllUserDetailsCtrl');


$app->group('/login',function(){
  $this->get('', RegisterLoginCtrl::class . ':loginHome');
  $this->post('/success', RegisterLoginCtrl::class . ':loginSuccess')->setName('login-success-page');
});


$app->post('/events', EventsCtrl::class . ':addEvents');



?>
