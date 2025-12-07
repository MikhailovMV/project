<?php
require __DIR__ . '/../config/database.php';


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
  public static function delete_project($id){

      $project = ORM::for_table('projects')->find_one($id);
      $project->delete();

      return $project;
    
    }


    
    

}



?>