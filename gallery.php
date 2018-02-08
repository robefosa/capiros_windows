<?php
include_once 'header.php';
include_once 'class/thumbnail.php';
include_once 'data/common_data.php';

//erase from temporal after finished script
$temp_files = array();
?>

<div id="project" class="container">

<?php 

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
    echo $ex->getMessage();
    include_once 'data/error_message_db.php';
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

$array_position = $actual_page * 3;

$get_total = "SELECT COUNT(*) FROM proyects";
$sql_p = "SELECT * FROM proyects ORDER BY display_order LIMIT ". $array_position . ", 3";
$sql_i = "SELECT * FROM images";

$total = $conn->query($get_total);
$row = $total->fetch_row();
$num_proyects = $row[0];

$result_p = $conn->query($sql_p);
$result_i = $conn->query($sql_i);


for($i = 1; $i <= $result_p->num_rows; $i++)
{
    $row_p = $result_p->fetch_assoc();  
    $result_i->data_seek(0);
    
    //finds principal image for the actual proyect
    for($j = 0; $j <= $result_i->num_rows; $j++)
    {
        $row_i = $result_i->fetch_assoc();
        
        if($row_i["imagen_id"] == $row_p["principal_img"])
        {
            $thum = new thumbnail($row_i["location"]);
            $image = $thum->create_thum_cent("temp/", 455, 350);
            $temp_files[] = $image;
        }    
    }
    ?>
    <section  class="row">
        <div class="col-md-7">
          <a href=<?php echo '"proyect_view.php?proyect='. $row_p["proyect_id"] . '"';?>>
              <img class="img-fluid rounded mb-3 mb-md-0" src=<?php echo $image;?> alt="">
          </a>
        </div>
        <div class="col-md-5">
          <h3><?php echo $row_p["proyect_name"];?></h3>
          <p><?php echo $row_p["description"]; ?></p>
          <a class="btn btn-primary" href=<?php echo '"proyect_view.php?proyect='. $row_p["proyect_id"] . '"';?>>View Project</a>
        </div>
    </section>
    <hr>
 <?php  
}
//store temp files names in a sesssion array
$_SESSION['temp'] = $temp_files;
 
//pagination
       
//get number of pages
$modulo = $num_proyects % 3;
if($modulo == 0)
{
    $num_pages = $num_proyects / 3;
}
else 
{
    $num_pages = (int) floor($num_proyects / 3) + 1;
}

//if less than 3 proyects do not show any pagination
if($num_proyects <= 3){}

//firts page (do not show Previus arrow)
elseif($actual_page == 0)
{
    ?><ul id="pageul"class="pagination justify-content-center"><?php
       
        
        
            if($num_pages > 3){$top = 2;}
            else{$top = $num_pages - 1;}
            
            for($i = 0; $i <= $top; $i++ )
            {?>
                <li class="page-item">
                    <a class="page-link <?php if ($i == $actual_page){echo 'actual';}?>" href=<?php echo '"gallery.php?page=' . $i . '"';?>><?php echo ($i + 1); ?></a>
                </li>
                <?php
            }  
        ?>
        <li class="page-item">
            <a class="page-link" href=<?php echo '"gallery.php?page='. ($actual_page + 1) . '"';?> aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
            <span class="sr-only">Next</span>
          </a>
        </li>
      </ul>

    <br><br><br><br>
</div>
<?php
 }
 //last page (do not show Next arrow)
 elseif ($actual_page == ($num_pages - 1)) 
{
      ?>
       <ul id="pageul"class="pagination justify-content-center">
        <li class="page-item">
            <a class="page-link" href=<?php echo '"gallery.php?page='. ($actual_page - 1) . '"';?> aria-label="Next">
            <span aria-hidden="true">&laquo;</span>
            <span class="sr-only">Previus</span>
          </a>
        </li>
        
    <?php
            
            if($num_pages > 3){$top1 = 2;}
            else{$top1 = $num_pages - 1;}
            
            for($i = ($num_pages - $top1); $i <= $num_pages; $i++ )
            {?>
                <li class="page-item">
                    <a class="page-link <?php if ($i == $num_pages){echo 'actual';}?>" href=<?php echo '"gallery.php?page=' . ($i - 1) . '"';?>><?php echo $i; ?></a>
                </li>
                <?php
            }       
        ?>
                </ul>
               </div>
            <br><br><br><br>
        <?php
}

 else
{?>
     <ul id="pageul"class="pagination justify-content-center">
        <ul id="pageul"class="pagination justify-content-center">
        <li class="page-item">
            <a class="page-link" href=<?php echo '"gallery.php?page='. ($actual_page - 1) . '"';?> aria-label="Next">
                <span aria-hidden="true">&laquo;</span>
            <span class="sr-only">Previus</span>
          </a>
        </li>
        
        <?php 
        
           for($i = $actual_page - 1; $i <= ($actual_page + 1); $i++ )
            {?>
                <li class="page-item">
                    <a class="page-link <?php if ($i == $actual_page){echo 'actual';}?>" href=<?php echo '"gallery.php?page=' . $i . '"';?>><?php echo ($i + 1); ?></a>
                </li>
                <?php
            }  
        
        ?>
                
        <li class="page-item">
            <a class="page-link" href=<?php echo '"gallery.php?page='. ($actual_page + 1) . '"';?> aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
            <span class="sr-only">Next</span>
          </a>
        </li>
      </ul>

    </div>
<br><br><br><br>
    
<?php
 }

include_once 'footer.php';
?>