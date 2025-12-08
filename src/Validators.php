<?php
require_once __DIR__ . '/../config/database.php';

class Validators {

   public static function is_status_id_exist($id){
      $result = Array(); //
      $result = ORM::for_table('statuses')->where('status_id', $id)->find_array();
      if(!empty($result)){ $res = True; } else { $res = False; }
      return $res;
    
    }

  public static function is_platform_id_exist($id){
      $result = Array(); //
      $result = ORM::for_table('platforms')->where('platform_id', $id)->find_array();
      if(!empty($result)){ $res = True; } else { $res = False; }
      return $res;
    
    }

}

?>