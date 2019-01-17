<?php
namespace App\models;
use App\utils\DbConnect;
class LoginOperations
{
  public $db = null;
  private $conn = null;
  public $registerOperations = null;

  public function __construct()
  {
    $this->db = new DbConnect();
    $this->conn = $this->db->getConnect();
    $this->registerOperations = new RegisterOperations();
  }

  public function loginUser($datas)
  {
    $user_email = $datas['user_email'];
    $password = $datas['password'];
    // echo $user_email;
    // echo $password;
    $datas = array();
    $sql = "select * from users where user_email = ? and password = ?";
    $stmt = $this->conn->prepare($sql);
    if(!$stmt)
    {
      return json_encode(
        array('message' => 'no post found')
      );
    }
    else {
      $stmt->bind_param("ss",$user_email,$password);
      $stmt->execute();
      $result = $stmt->get_result();
      while($row = $result->fetch_assoc())
                  {
                      array_push($datas,$row);
                  }
      // print_r($datas);
      $_SESSION['user_id'] = $datas[0]['user_id'];
      $_SESSION['role_type'] = $datas[0]['role_id'];
      return true;
    }

  }


}
 ?>
