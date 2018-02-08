
<section  class="row">
        <div class="col-md-4">
            <h4><strong>Imagen principal del proyecto</strong></h4>
            <hr>
          <div class="view_i_container">
                <a class="" href=<?php echo '"proyect_view.php?proyect='. $pro->get_property("proyect_id") . '"';?>>
                    <img class="p_image" src=<?php echo "../../" . $principal['location'];?> alt="">
                </a>
            </div>
        </div>
    
        <a class="link-position col-md-3" href=<?php echo '"proyect_edit.php?proyect='. $pro->get_property("proyect_id") . '"';?>>Actualizar este proyecto</a>
        
        <div class="col-md-5">
            <h4><strong>Nombre del proyecto</strong></h4>
            <h5><em><?php echo $pro->get_property("proyect_name");?></em></h5>
          <hr>
          <h4><strong>Descripcion del proyecto</strong></h4>
          <p><em><?php echo $pro->get_property("description"); ?></em></p>
          
        </div>
</section>
    <hr><hr>