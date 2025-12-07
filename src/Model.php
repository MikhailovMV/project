<?php
require __DIR__ . '/../config/database.php';


class ModelProjects {

  public static function project_list(){
    
    //$project_list = ORM::forTable("projects")->find_array();


  $results = ORM::for_table('projects')
      ->table_alias('p1')
    //  ->select('p1.id, p1.status')
    //  ->select('p3.platform_name')
    //  ->select('p2.status_name')
     // ->select('p1.date_create', 'p1.date_update')
    //  ->join('platfors', array('p1.platform', '=', 'p2.platform_id'), 'p2')
    //  ->join('statuses', array('p1.status', '=', 'p3.status_id'), 'p3')
      ->find_many();

      return $results;
    
    }

}
//$project = ORM::for_table('projects')->create();

    //$project->t = '456789';
    //$project->age = 40;

    //$project->save();
?>