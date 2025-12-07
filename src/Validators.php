<?php
    $platform = "";
    $status = "";
    $page = "";
    $limit = "";

    if (!empty($_GET['platform'])){
        $platform = $_GET['platform'];
    }
    if (!empty($_GET['status'])){
        $status = $_GET['status'];
    }
    if (!empty($_GET['page'])){
        $page = $_GET['page'];
    }
    if (!empty($_GET['limit'])){
        $limit = $_GET['limit'];
    }

?>