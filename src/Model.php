<?php
require __DIR__ . '/../config/database.php';


class ModelProjects {

  public static function project_list(){
    
    $project_list = ORM::forTable("projects")->find_array();
    return $project_list;
  
  }

}
//$project = ORM::for_table('projects')->create();

    //$project->t = '456789';
    //$project->age = 40;

    //$project->save();
?>