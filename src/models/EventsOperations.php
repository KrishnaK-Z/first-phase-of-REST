<?php

namespace App\models;
use App\utils\DbConnect;
class EventsOperations
{

  public $db = null;
  public $conn = null;
  public $registerOperations = null;

  public function __construct()
  {
    $this->db = new DbConnect();
    $this->conn = $this->db->getConnect();
    $this->registerOperations = new RegisterOperations();
  }

  public function addEvents($datas)
  {
    $event_id = $datas['event_id'];
    $event_name = $datas['event_name'];
    $event_date = $datas['event_date'];
    $event_category_name = $datas['event_category_name'];
    $coordinator_id = $_SESSION['user_id'];
    $start_time = $datas['$start_time'];
    $end_time = $datas['end_time'];
    $street_address = $datas['street_address'];
    $area = $datas['area'];
    $pin_code = $datas['pincode'];

    $event_category_id = $this->getEventCategoryId($event_category_name);

    if($event_category_id > 1)
    {
      if(!$this->getAddressId($street_address, $area, $pin_code))
      {
        $this->registerOperations->insertAddressDetails($street_address, $area, $pin_code);
      }
      $address_id = $this->getAddressId($street_address, $area, $pin_code);
      $sql_insert_new_event = "insert into events(event_name, event_category_id, coordinator_id, event_date, start_time, end_time, address_id) VALUES (?,?,?,?,?,?,?)";
      $stmt = $this->conn->prepare($sql_insert_new_event);
      if(!$stmt)
      {
        echo json_encode(
          array("message" => "Error in the insertion query");
        );
      }
      else {
        $stmt->bind_param("siisssi", $event_name, $event_category_id, $coordinator_id, $event_date, $start_time, $end_time, $address_id);
        $stmt->execute();
        return true;
      }
      return false;
    }
    return fasle;
  }

  public function getEventCategoryId($event_category_name)
  {
    $sql_get_event_cat_id = "select event_category_id from event_category where event_category_name = ?";
    $stmt = $this->conn->prepare($sql_get_event_cat_id);
    if(!$stmt)
    {
      return false;
    }
    else {
      $stmt->bind_param("s",$event_category_name);
      $stmt->execute();
      $result = $stmt->get_result();
      if($result->num_rows > 0)
      {
        $event_category_id = $result->fetch_assoc();
        return $event_category_id;
      }
      return false;
    }
    return false;
  }



  public function getAddressId($street_address, $area, $pin_code)
  {
    $address_datas = array();
    $sql_get_address_id = "select address_id from address_details where street_address = ? and area = ? and pincode = ?";
    $stmt = $this->conn->prepare($sql_get_address_id);
    if(!stmt)
    {
      return false;
    }
    else {
      $stmt->bind_param("ssi",$street_address,$area,$pin_code);
      $stmt->execute();
      $result = $stmt->get_result();
      if($result->num_rows > 0)
      {
        while($row = $result->fetch_assoc())
        {
          array_push($address_datas,$row);
        }
        return $address_datas[0]['address_id'];
      }
      return false;
    }
    return false;
  }

  public function getEventsOfUser()
  {
    $datas = array();
    $sql = "select * from ( ( events inner join users on events.coordinator_id = users.user_id ) inner join address_details on events.address_id = address_details.address_id )";
    $stmt = $this->conn->prepare($sql);
    if(!$stmt)
    {
      return json_encode(
        array("message" => "Query Error");
      );
    }
    else {
      $stmt->execute();
      $result = $stmt->get_result();
      if($result->num_rows > 1)
      {
        while($row = $result->fetch_assoc())
        {
          array_push($datas,$row);
        }
        return $datas;
      }
      else {
        return json_encode(
          array("message" => "No Events");
        );
      }
      return false;
    }
    return false;
  }


}

 ?>
