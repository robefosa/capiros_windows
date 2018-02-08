<?php
session_start();
if($_SESSION['ok'])
{
include_once '../../data/common_data.php';
include_once '../classes/upload.php';
include_once '../classes/proyect.php';

$error_message = FALSE;

//creat an empty proyect

try 
{
    $pro = new proyect($total_control_user_name, $total_control_user_pass, $data_base_name, "proyects", -1, "proyect_id");
    } 
    catch (Exception $ex) {
        $error_message = $ex->getMessage();
    }
   
    
       if(isset($_POST['create']))
        {
            $pro_data['proyect_name'] = trim($_POST['p_name']);
            $pro_data['display_order'] = trim($_POST['order']);
            $pro_data['description'] = trim($_POST['p_description']);
            
          if(!$pro->create_proyect($pro_data))
          {  
                $error_message = $pro->get_error();
          }
          else 
              {
                    $str = "Location: proyect_edit.php?proyect=" . $pro->get_property("proyect_id");
                    header($str);
              }
                
        }
               
              
        //start sending content to browser
include_once '../comon/dashboard_header.php';
      
  ?>


        <section class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-2">
                      <!--show error message if something goes wrong-->
                <?php
                        if($error_message != FALSE)
                            echo '<h3 style="color: red;">' . $error_message . '</h3>';
                        ?>
                </div>
                
            </div>
            <div class="row">
                       
                <!--Proyect name and description -->
                <div class="col-md-5 col-md-offset-2">
                    <hr style="border-color: black;"> 
                    
                    <form name="proyect_data"  method="post" action="" onsubmit="return checkData(this);" enctype="multipart/form-data">
                        <input name="proyect" type="hidden" value="">
                        
                        <h3>Introduzca un nombre para el proyecto</h3>
                        <input id="p_name" name="p_name" id="p_name" type="text" style="width: 100%;">
                        <br><br>
                        
                        <h3>Introduzca una brebe descripcion del proyecto</h3>
                        <textarea id="p_description" name="p_description" id="p_description" style="width: 100%; height: 100px;">
                            
                        </textarea>
                        
                        <br><br>
                 
                        <h3>Numero de orden a mostrar</h3>
                        <input id="order" name="order" type="text" style="width: 15%;">
                            
                        <br><br>
                        
                       <input id="create" name="create" type="submit" style="background: #ebebe0;" value="Crear proyecto">
                        
                    </form>
                    
                </div>
        </section>

<?php
  

include_once '../comon/dashboard_footer.php';
}
 else {
    header("Location: ../index.php");
}