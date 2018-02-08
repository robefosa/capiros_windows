<?php
session_start();
if($_SESSION['ok'])
{
include_once '../comon/dashboard_header.php';
include_once '../../data/common_data.php';
include_once '../../class/thumbnail.php';
include_once '../../functions/pagination.php';
include_once '../classes/proyect.php';
include_once '../classes/proyects_handler.php';
?>

<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Lista de proyectos</h1>
                    <p class="lead text-center">Los siguientes proyectos se muestran en su sitio web.</p>
                </div>
                <!-- /.col-lg-12 -->
            </div>
    
    <?php 

    $error_message = FALSE;
//MySQL connection

try 
{
    $conn = new mysqli("localhost", $read_only_user_name, $read_only_user_pass, $data_base_name);
     
} catch (Exception $ex) 
{ 
    echo $ex->getMessage();
    include_once $server_base_url . 'data/error_message_db.php';
}

//check for actual page
if (isset($_GET["page"]) && is_numeric($_GET["page"]))
{
    $actual_page = $_GET["page"];
    
}
 else 
{
    $actual_page = 0;
}

$array_position = $actual_page * 6;

//get number of proyects
try{
    $handler = new proyects_handler($read_only_user_name, $read_only_user_pass, $data_base_name, "proyects");
}
 catch (Exception $ex){
    echo $ex->getMessage();
     
 }

$num_proyects = $handler->get_number_proyects();


$sql_p = "SELECT proyect_id FROM proyects ORDER BY display_order LIMIT ". $array_position . ", 6";
$result_p = $conn->query($sql_p);
       

for($i = 1; $i <= $result_p->num_rows; $i++)
{
    //get actual proyect
    $row_p = $result_p->fetch_row(); 
    $actual_proyect = $row_p[0];
    
    try {
        $pro = new proyect($total_control_user_name, $total_control_user_pass, $data_base_name,
                "proyects", $actual_proyect, "proyect_id");
    } catch (Exception $ex) {
        include_once $server_base_url . 'data/error_message_db.php';
    }
    
    //get all images in actual proyect
        $imagenes = $pro->get_images_data();
        $principal = $pro->get_principal_image_data();
    
    switch ($_GET['p'])
    {
        case "p_list":
            include '../pages/p_list.php';
            break;
        case "p_add":
            include '../pages/p_add.php';
            break;
        case "p_update":
            include '../pages/p_update.php';
            break;
        case "p_delete":
            include '../pages/p_delete.php';
            break;
        default :
            include '../pages/p_list.php';
            break;
            
    }  
}
//pagination
$script_path = "proyect_list.php?emty=1";

pagination($num_proyects, 6, $actual_page, $script_path);
?>
    
    
</div>

<?php
include_once '../comon/dashboard_footer.php';

}
 else {
    header("Location: ../index.php");
}