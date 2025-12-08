<?php
require_once __DIR__ . '/../config/database.php';
/*

 * Класс валидации взаимодействия с базой данных
 *  */
class Validators {
    /**
     *
     * Метод проверяет существование ID статуса проекта в таблице базы данных.
     *
     * @param int        $id ID статуса проекта.
     * 
     * @return bool
     */
    public static function is_status_id_exist($id){
        $result = Array(); //
        $result = ORM::for_table('statuses')->where('status_id', $id)->find_array();
        if(!empty($result)){ $res = True; } else { $res = False; }
        return $res;

    }
    /**
     *
     * Метод проверяет существование ID платформы проекта в таблице базы данных.
     *
     * @param int        $id ID платформы проекта.
     * 
     * @return bool
     */
    public static function is_platform_id_exist($id){
        $result = Array(); //
        $result = ORM::for_table('platforms')->where('platform_id', $id)->find_array();
        if(!empty($result)){ $res = True; } else { $res = False; }
        return $res;
    
    }

}

?>