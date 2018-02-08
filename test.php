<?php
include_once 'data/common_data.php';
include_once 'class/thumbnail.php';

mysqli_report(MYSQLI_REPORT_STRICT);


$user = "capiro_read";
$pass = "read12345";
$db = "capiro";

try 
{
    $conn = new mysqli("localhost", $user, $pass, $db);
    echo 'conexion exitosa';
    
} catch (Exception $ex) {
    echo $ex->getMessage();
}

$sql = "SELECT * FROM proyects ORDER BY display_order";





