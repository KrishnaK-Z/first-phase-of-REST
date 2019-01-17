<?php
namespace App\Interface;


interface users
{
  public datas = null;
  public user_id;
  public user_name;
  public password;
  public user_email;
  public user_type_id;
  public fucntion __construct($datas)
  {
    $this->datas = $datas;
  }

  public function setName()
  {
    $this->user_name = $this->datas['user_name'];
  }
  public function getName()
  {
    return $this->user_name;
  }

  public function setUserEmail()
  {
    $this->user_email = $this->datas['user_email'];
  }
  public function getUserEmail()
  {
    return $this->user_email;
  }

  public 


}

 ?>
