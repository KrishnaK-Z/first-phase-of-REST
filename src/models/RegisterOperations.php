<?php
namespace App\models;
use App\utils\DbConnect;
// use Slim\Http\UploadedFile;

class RegisterOperations
{
  public $db = null;
  public $conn = null;

  public function __construct()
  {
    $this->db = new DbConnect();
    $this->conn = $this->db->getConnect();
  }

  public function summa()
  {
    echo "hi there!!!";
  }
//datas passed to the register function is in json format
  public function registerUser($datas)
  {
    // print_r($datas);
    $user_name = $datas['user_name'];
    $user_email = $datas['user_email'];
    $password = $datas['password'];
    $phone_number = $datas['phone_number'];
    $role_type = $datas['role_type'];
    $street_address = $datas['street_address'];
    $area = $datas['area'];
    $pin_code = $datas['pincode'];
    $organisation_website = $datas['organisation_website'];
    $profile_pics = $datas['uploadedProfilePic'];
    $directory = "profile_pics/";
    $fileLocation = null;

    $role_id = $this->getRoleId($role_type);
    $role_id_var = $role_id['roles_id'];

    if( ($role_id_var == 1) || ($role_id_var == 3) )
    {
      if(!$this->getUserDetailsByEmail($user_email))
      {

        if ($profile_pics->getError() === UPLOAD_ERR_OK) {
            $fileLocation = $this->moveUploadedFile($directory, $profile_pics);
            // echo "success";
        }
        $sql = "insert into users ( user_name, user_email, password, profile_pic, phone_number, role_id, address_id) values (?,?,?,?,?,?,?)";
        $stmt = $this->conn->prepare($sql);

        if(!$stmt)
        {
            return false;
        }
        else {

          if($this->getAddressId($street_address, $area, $pin_code)==0)
          {
              $this->insertAddressDetails($street_address, $area, $pin_code);
          }

          $address_id = $this->getAddressId($street_address, $area, $pin_code);
          $stmt->bind_param("ssssiii",$user_name, $user_email, $password, $fileLocation, $phone_number, $role_id_var, $address_id['address_id']);
          $stmt->execute();
          $stmt->close();
          $this->db->dbFinish();
          return true;
        }
      }
      return false;
    }
    else if($role_id_var==2)
    {
      if(!$this->getOrganisationDetails($organisation_name))
      {
      $sql = "insert into users (user_name, user_email, password, phone_number, organisation_website, role_id) VALUES (?,?,?,?,?,?)";
      $stmt = $this->conn->prepare($sql);
      if(!$stmt)
      {
          return false;
      }
      else {
        $stmt->bind_param("sssisi",$user_name, $user_email, $password,$phone_number, $organisation_website, $role_id_var);
        $stmt->execute();
        $stmt->close();
        $this->db->dbFinish();
        return true;
      }
    }
    return false;
    }
    else {
      return false;
    }
    return false;
  }

  function moveUploadedFile($directory, $uploadedFile)
  {
      $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
      $basename = bin2hex(random_bytes(8));
      $filename = sprintf('%s.%0.8s', $basename, $extension);

      $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

      return $directory.$filename;
  }


    public function insertAddressDetails($street_address, $area, $pin_code)
    {
        $sql = "insert into address_details (street_address, area, pincode) values (?,?,?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss",$street_address, $area, $pin_code);
        $stmt->execute();
        $stmt->close();
    }

    public function insertIntoUserDetials($user_name,$user_email,$password,$phone_number,$role_id_var,$address_id)
    {
      $sql = "insert into users ( user_name, user_email, password, phone_number, role_id, address_id) values (?,?,?,?,?,?)";
      $stmt = $this->conn->prepare($sql);
      $stmt->bind_param("sssiii",$user_name, $user_email, $password, $phone_number, $role_id_var, $address_id);
      $stmt->execute();
      $stmt->close();
    }


  //verified
  public function showAllUserDetails()
  {
    $user_details_data = array();
    $sql = "select * from users inner join address_details on users.address_id = address_details.address_id";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows>0)
    {
      while($row = $result->fetch_assoc())
      {
        array_push($user_details_data, $row);
      }
      return $user_details_data;
    }
    else {
        return false;
    }
    return false;
  }

  //VERIFIED
  public function showAllOrganisationDetails()
  {
    $organisation_datas = array();
    $sql = "select * from users";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows>0)
    {
      while($row = $result->fetch_assoc())
      {
        array_push($organisation_datas, $row);
      }
      return $organisation_datas;
    }
    else {
        return false;
    }
     return false;
  }


//VERIFIED
  public function getOrganisationDetails($organisation_name)
  {
    $organisation_datas = array();
    $sql = "select * from users where user_name = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s",$organisation_name);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows>0)
    {
      while($row = $result->fetch_assoc())
      {
        array_push($organisation_datas, $row);
      }
      return $organisation_datas;
    }
    else {
        return false;
    }
     return false;
  }


//verified
  public function getUserDetailsByEmail($user_email)
  {
    $user_details_data = array();
    $sql = "select * from users where user_email = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s",$user_email);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows>0)
    {
      while($row = $result->fetch_assoc())
      {
        array_push($user_details_data, $row);
      }
      return $user_details_data;
    }
    else {
        return false;
    }
    return false;
  }


//verified
  public function getRoleId($role_type)
  {
    $sql_get_role_id = "select roles_id from roles where role_type = ?";
    $stmt = $this->conn->prepare($sql_get_role_id);
    $stmt->bind_param("s",$role_type);
    $stmt->execute();
    $result = $stmt->get_result();
     if($result->num_rows > 0)
     {
       $role_id=$result->fetch_assoc();
       return $role_id;
     }
     return false;
  }


//VERIFIED
  public function getAddressId($street_address, $area, $pin_code)
  {
      $sql_get_address_id = "select address_id from address_details where street_address = ? and area = ? and pincode = ?";
      $stmt = $this->conn->prepare($sql_get_address_id);
      $stmt->bind_param("sss",$street_address,$area,$pin_code);
      $stmt->execute();
      $result = $stmt->get_result();
      if($result->num_rows > 0)
      {
        $address_id = $result->fetch_assoc();
        return $address_id;
      }
      return 0;
  }

}
?>
