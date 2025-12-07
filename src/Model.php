<?php
require __DIR__ . '/../config/database.php';


class ModelProjects {

  public static function project_list(){
    
    //$project_list = ORM::forTable("projects")->find_array();


  $results = ORM::for_table('projects')
      ->select('projects.*')
      ->select('platforms.platform_name')
      ->select('statuses.status_name')
      ->join('platforms', array('projects.platform', '=', 'platforms.platform_id'))
      ->join('statuses', array('projects.status', '=', 'statuses.status_id'))
      ->find_array();

      return $results;
    
    }

}
//$project = ORM::for_table('projects')->create();

    //$project->t = '456789';
    //$project->age = 40;

    //$project->save();
?>