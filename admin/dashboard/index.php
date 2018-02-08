<?php
session_start();
if($_SESSION['ok'])
{
include_once '../../data/common_data.php';
include_once '../comon/dashboard_header.php';
?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Inicio</h1>
                    <p class="lead text-center">Bienvenido a la pantalla principal de su panel de control. Desde aqui udsted puede manejar 
                    la informacion que se muestra en su sitio web. Para adicionar, actualizar o eliminar cualquier
                    informacion, por favor haga clic en la categoria correspondiente.</p>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-3">
                  <div class="thumbnail">
                      <img src="../data/list.png" alt="...">
                    <div class="caption">
                        <a href="../dashboard/proyect_list.php?p=p_list">
                            <h4>Lista de proyectos</h4>
                        </a>
                    </div>
                  </div>
                </div>
                
                <div class="col-md-3">
                  <div class="thumbnail">
                    <img src="../data/add.png" alt="...">
                    <div class="caption">
                        <a href="../dashboard/new_proyect.php">
                            <h4>Agregar un proyecto</h4>
                        </a>
                    </div>
                  </div>
                </div>
                
                <div class="col-md-3">
                  <div class="thumbnail">
                    <img src="../data/update.png" alt="...">
                    <div class="caption">
                        <a href="../dashboard/proyect_list.php?p=p_update">
                            <h4>Actualizar un proyecto</h4>
                        </a>
                    </div>
                  </div>
                </div>
                
                <div class="col-md-3">
                  <div class="thumbnail">
                    <img src="../data/delete.png" alt="Clic en el link inferior para borrar un proyecto">
                    <div class="caption">
                        <a href="../dashboard/proyect_list.php?p=p_delete">
                            <h4>Eliminar un proyecto</h4>
                        </a>
                    </div>
                  </div>
                </div>
              </div>
            
  
            
               <!-- /#page-wrapper -->

         </div>
    <!-- /#wrapper -->

  <?php
  
  include_once '../comon/dashboard_footer.php';
}
 else {
    header("Location: ../index.php");
}