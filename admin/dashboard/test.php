<?php

include_once '../../data/common_data.php';
include_once '../classes/proyect.php';
include_once '../classes/login.php';

try{
$log = new login($total_control_user_name, $total_control_user_pass, $data_base_name, "login");
} catch (Exception $ex){
    echo $ex->getMessage();
}

if(!$log->create_user("yusviel", "capiro", "contact@capiroswindows.com"))
    echo $log->get_error();


