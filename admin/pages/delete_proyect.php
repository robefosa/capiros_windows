<?php
include_once '../../data/common_data.php';
include_once '../classes/proyect.php';

    if($_GET && is_numeric($_GET['p']))
    {
            
        $pro = new proyect($total_control_user_name, $total_control_user_pass, $data_base_name, "proyects", 
                $_GET['p'], 'proyect_id');
        
        if(!$pro->delete_proyect())
        {
            echo $pro->get_error ();
        }
        else {
            header("location: ../dashboard/proyect_list.php?p=p_delete");
        }
      
        
        
    }