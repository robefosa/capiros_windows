<?php
session_start();
if($_SESSION['ok'])
{
include_once '../../data/common_data.php';
include_once '../classes/upload.php';
include_once '../classes/proyect.php';

$error_message = FALSE;

if(isset($_GET["proyect"]) && is_numeric($_GET["proyect"]))
//check for real proyect number in $_GET array
{
    try {
        $pro = new proyect($total_control_user_name, $total_control_user_pass, $data_base_name, "proyects", $_GET['proyect'], "proyect_id");
        } catch (Exception $ex) {
            $error_message = $ex->getMessage();
        }

    if($pro->exist())
    {
        //update proyect data
        if (isset($_POST['name_description']))
        {
           if($pro->update_property("proyect_name", $_POST['p_name']) && $pro->update_property("description", trim($_POST['p_description'])))
            {
                header ("Refresh:0");
            }
            else 
                {
                    $error_message = $pro->get_error();
                }
        }
        
        //add images
        if(isset($_POST['new_images']))
        {
            if($pro->add_image($_FILES['images']))
                header ('Refresh:0');
            else
                {
                    $error_message = $pro->get_error();
                }
        }
        
        //delete imagen
        if(isset($_POST['eliminar']))
        {
            if($pro->delete_image($_POST['form_id']))
                header ("Refresh:0");  
            else 
            {
                $error_message = $pro->get_error();
            }
           
        }
        
        //Update imagen
        if(isset($_POST['cambiar']))
        {
           
           if($pro->update_image($_FILES['upload'], $_POST['form_id']))
               header ('Refresh:0');
           else {
                $error_message = $pro->get_error();
           }
        }
        
        //make imagen principal
        if(isset($_POST['principal']))
        {
            if($pro->set_principal_image($_POST['form_id']))
                header('Refresh:0');
            else {
                $error_message = $pro->get_error();
            }
        }
               
        $imagenes = $pro->get_images_data();
        $principal = $pro->get_principal_image_data();
        
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
                    
                    <form name="proyect_data"  method="post" action="" enctype="multipart/form-data">
                        <input name="proyect" type="hidden" value="<?php echo $pro->get_property("proyect_id"); ?>">
                        
                        <h3>Nombre del proyecto</h3>
                        <input name="p_name" id="p_name" type="text" style="width: 100%;"value="<?php echo $pro->get_property("proyect_name");?>">
                        <br><br>
                        
                        <h3>Descripcion del proyecto</h3>
                        <textarea name="p_description" id="p_description" style="width: 100%; height: 100px;">
                            <?php echo $pro->get_property("description"); ?>
                        </textarea>
                        
                        <br><br>
                        <input name="name_description" type="submit" style="background: #ebebe0;" value="Actualizar Nombre o Descripcion del Proyecto">
                        
                        <br><br>
                        <input name="images[]" type="file" style="background: #ebebe0;" multiple>
                        <br>
                        <input name="new_images" type="submit" style="background: #ebebe0;" value="Agregar imagenes">
                        
                    </form>
                    
                </div>

                <!--Principal imagen-->
                <div class="col-md-5">
                    <hr style="border-color: black;">
                    <h3>Imagen Principal</h3>
                    <div class="i_container">
                        <img class="p_image" src="<?php echo "../../" . $principal['location']; ?>">
                        
                    </div>

                </div>
            </div>
            
            <?php
                 if ($imagenes && !empty($imagenes))
                {
                     ?>
        <!--Principal separator -->   
            <div class="row">
                <div class="col-md-12">
                    <hr style="border-color: black;">
                    <h4>Por favor asegurese de que solo una imagen este selecionada como imagen principal</h4>
                </div>
            </div>
        
        <!-- proyect images list-->
            
            <?php
               
                    for($i=1; $i <= count($imagenes); $i++)
                    {
                        //decide when to change line
                        $change = $i%4;
                        if ($i == 1 || $change == 1)
                        {
                            ?>
                             <div class="row">
                            <?php
                        }
                      ?>
                        <div class="col-md-3">
                            <div class="image_cont">
                                <img class="imag" src="<?php echo "../../" . $imagenes[$i - 1]['location'];?>">
                            </div>
                            <br>
                            <form name="image_ <?php echo $imagenes[$i - 1]['imagen_id']; ?>" method="post" action="" enctype="multipart/form-data">
                                <input name="form_id" type="hidden" value="<?php echo $imagenes[$i - 1]['imagen_id'];?>">
                                <input name="img_path" type="hidden" value="<?php echo $imagenes[$i - 1]['location'];?>">
                                
                                <input name="principal" type="submit" value="Convertir en Principal" style="background: #ebebe0;">
                                <input name="eliminar" type="submit" value="Eliminar" style="background: #ebebe0;">
                                <br><br>
                                <input name="upload[]" type="file" value="upload" style="background: #ebebe0;" multiple>
                                <br>
                                <input name="cambiar" type="submit" value="Cambiar Imagen" size="30px">
                            </form> 
                            
                            <br><br>
                            <hr style="border-color: black;">
                        </div>
                        <?php
                        //close row
                        if ($i == count($imagenes) || $change == 0)
                        {
                            ?>
                             </div>
                            <?php
                        }
                    }
                    
                }
                 else {
                    include_once '../../data/error_message_no_imagen.php';
                }
                
                
                    
            ?>
        </section>

<?php
    }
 else {
     echo '<br><br><br>';
    include_once '../../data/error_message_db.php';
    echo '<br><br><br>';
}
}
else 
{
    echo '<br><br><br>';
    include_once '../../data/error_message_db.php';
    echo '<br><br><br>';
}

include_once '../comon/dashboard_footer.php';

}
 else {
    header("Location: ../index.php");
}