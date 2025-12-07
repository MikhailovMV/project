<?php
require __DIR__ . '/../config/database.php';

function mysql_escape($str){
  return $str;
}

class ModelProjects {

  public static function project_list(){

      $results = ORM::for_table('projects')
        ->select('projects.*')
        ->select('platforms.platform_name')
        ->select('statuses.status_name')
        ->join('platforms', array('projects.platform', '=', 'platforms.platform_id'))
        ->join('statuses', array('projects.status', '=', 'statuses.status_id'))
        ->order_by_asc('projects.id')
        ->find_array();

      return $results;
    
    }


  public static function insert_project($line){

      $project = ORM::for_table('projects')->create();

      $project->name = $line['name'];
      $project->url = $line['url']; 
      $project->platform = $line['platform'];
      $project->status = $line['status']; 
      $project->description = $line['description']; 
      $project->date_create = $line['date_create']; 
      $project->date_update = $line['date_update'];

      $project->save();

      return $project;
    
    }

  public static function get_project_by_id($id){

      $results = ORM::for_table('projects')->select('projects.*')
        ->select('platforms.platform_name')
        ->select('statuses.status_name')
        ->join('platforms', array('projects.platform', '=', 'platforms.platform_id'))
        ->join('statuses', array('projects.status', '=', 'statuses.status_id'))
        ->where('id', $id)->find_array();;
      return $results;
    
    }

   public static function project_list_filtered($platform, $status, $page, $limit){
      $where = "WHERE ";
      $limit_sql = " LIMIT ";
      if(!empty($platform) && !empty($status)){
          $where .= "platforms.platform_name = '".mysql_escape($platform)."' AND statuses.status_name = '".mysql_escape($status)."'";
      }elseif(empty($platform) && !empty($status)){
          $where .= "statuses.status_name = '".mysql_escape($status)."'";
      }elseif(!empty($platform) && empty($status)){
          $where .= "platforms.platform_name = '".mysql_escape($platform)."'";
      }

      if(!empty($page) || !empty($limit)){
           $limit .= (int) $limit * (( int ) $page - 1) .", ".mysql_escape($limit);
      }

      $sql = "SELECT projects.*, platforms.platform_name, statuses.status_name 
                      LEFT JOIN platforms ON projects.platform = platforms.platform_id
                      LEFT JOIN statuses ON projects.status = statuses.status_id ". $where. $limit_sql;
       echo  $sql;              
      $results = ORM::for_table('projects')->raw_query($sql)->find_array();

      return $results;
    
    }

  public static function delete_project($id){

      $project = ORM::for_table('projects')->find_one($id);
      $project->delete();

      return $project;
    
    }


    
    

}



?>