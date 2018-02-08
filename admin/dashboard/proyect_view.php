<?php
session_start();
if($_SESSION['ok'])
{
include_once '../../data/common_data.php';
include_once $server_root_directory . 'admin/comon/dashboard_header.php';
include_once $server_root_directory . 'class/thumbnail.php';
include_once $server_root_directory . 'functions/pagination.php';

//erase from temporal after finished script
$temp_files = array();

//rows of pictures by page
$rows = 4;
//pictures by page
$page_pictures = 2 * $rows;

//MySQL connection
mysqli_report(MYSQLI_REPORT_STRICT);

$user = $read_only_user_name;
$pass = $read_only_user_pass;
$db = $data_base_name;

try 
{
    $conn = new mysqli("localhost", $user, $pass, $db);
     
} catch (Exception $ex) 
{ 
    include_once $server_base_url . 'data/error_message_db.php';
}

if(isset($_GET["proyect"]) && is_numeric($_GET["proyect"]))
//check for real proyect number in $_GET array
{
    $sql_p = "SELECT * FROM proyects WHERE proyect_id=" . $_GET["proyect"];
    
    //check if the proyect exists and get the data
    $result_p = $conn->query($sql_p);
    if($result_p->num_rows >= 1)
    {
        $proyect = $result_p->fetch_assoc();
        ?>  
        <div class="container">

            <section id="encabezado" class="bg-faded p-4 my-4">
                    <hr class="divider">
                    <h2 class="text-center text-lg text-uppercase my-0"><strong><?php echo $proyect['proyect_name'];?></strong></h2>

                    <hr class="divider">

                    <p class="home text-center"><?php echo $proyect['description'];?></p>
            </section>
            <section id="pictures" class="center-block">

        <?php 
            $get_images = "SELECT * FROM images WHERE proyects_proyect_id=" . $proyect['proyect_id'];
            
            $result_i = $conn->query($get_images);
            $num_pictures = $result_i->num_rows;
            
            //show only one page
            if($num_pictures <= $page_pictures)
            {
                for($i = 1; $i <= $result_i->num_rows; $i++)
                {
                    $img = $result_i->fetch_assoc();
                    $image = new thumbnail($server_root_directory . $img['location']);
                    $path = $image->create_miniature($temp_folder_path, 400);
                    $temp_files[] = $path;
                   
            //deside when to change line
                    $position = $i % 2;
                    if($i == 1 || $position == 1)
                    {
                        ?>
                        <div class="row"> 
                              <div class="column col-xs-12 col-md-6">
                                  <div class="first-image">
                                  <img class="" src="<?php echo $path;?>">
                                  </div>
                              </div>
                        <?php
                        if($i == $result_i->num_rows)
                        {
                            ?>
                                </div>
                            <?php
                        }
                    }
                    else 
                    {
                        ?>
                            <div class="column col-xs-12 col-md-6">
                                <div class="second-image">
                                  <img src="<?php echo $path;?>">
                                </div>
                                                                                            
                              </div>
                        </div>
                        <?php
                    }
                }
            }
            //show several pages with pagination
            else
            {
                //check for actual page
                if (isset($_GET["page"]) && is_numeric($_GET["page"]))
                {
                    $actual_page = $_GET["page"];

                }
                 else 
                {
                    $actual_page = 0;
                }
                
                // limit results to 8 images in each page 
                $array_position = $actual_page * 8;
                $images = "SELECT * FROM images WHERE proyects_proyect_id=" . $proyect['proyect_id'] . " LIMIT " . $array_position . ", 8";
                
                $result = $conn->query($images);
                
                for($i = 1; $i <= $result->num_rows; $i++)
                {
                    $img = $result->fetch_assoc();
                    $image = new thumbnail($server_root_directory . $img['location']);
                    $path = $image->create_miniature($temp_folder_path, 400);
                    $temp_files[] = $path;
                   
            //deside when to change line
                    $position = $i % 2;
                    if($i == 1 || $position == 1)
                    {
                        ?>
                        <div class="row"> 
                              <div class="column col-xs-12 col-md-6">
                                  <div class="first-image">
                                  <img class="" src="<?php echo $path;?>">
                                  </div>
                              </div>
                        <?php
                        if($i == $result->num_rows)
                        {
                            ?>
                                </div>
                            <?php
                        }
                    }
                    else 
                    {
                        ?>
                            <div class="column col-xs-12 col-md-6">
                                <div class="second-image">
                                  <img src="<?php echo $path;?>">
                                </div>
                                                                                            
                              </div>
                        </div>
            <!--for pagination-->
            
                         <?php
                    }
                }
                ?>
            </section>
                    <!--for pagination-->
            
                <?php
            //show pagination
                
                $script_path = "proyect_view.php?proyect=" . $_GET["proyect"];
                
            pagination($num_pictures, $page_pictures, $actual_page, $script_path);    
           
            }
           
            
    }
    else 
    {
        echo '<br><br><br>';
        include_once $server_root_directory . 'data/error_message_db.php';
        echo '<br><br><br>';
    }
}
 else 
{
    echo '<br><br><br>';
    include_once $server_root_directory . 'data/error_message_db.php';
    echo '<br><br><br>';
   
}

include_once '../comon/dashboard_footer.php';

}
 else {
    header("Location: ../index.php");
}


