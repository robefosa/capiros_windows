<script type="text/javascript">
                function confirmar(id)
                {
                    if(confirm("¿Esta seguro que desea eliminar este proyecto?"))
                    {
                        id.href = '../pages/delete_proyect.php?p=' + id.name;
                        
                    }
                    
                }
</script>
            
            
<section  class="row">
    <label class="p_id" hidden><?php echo $pro->get_property("proyect_id");?></label>
        <div class="col-md-4">
            <h4><strong>Imagen principal del proyecto</strong></h4>
            <hr>
            <div class="view_i_container">
                <a href=<?php echo '"proyect_view.php?proyect='. $pro->get_property("proyect_id") . '"';?>>

                      <img class="p_image" 
                           src=<?php echo "../../" . $principal['location'];?> alt="">

                </a>
            </div>
        </div>
    
        <a class="link-position col-md-3"name="<?php echo $pro->get_property("proyect_id");?>" onclick="confirmar(this);">   Eliminar este proyecto</a>
        
        <div class="col-md-5">
            <h4><strong>Nombre del proyecto</strong></h4>
            <h5><em><?php echo $pro->get_property("proyect_name");?></em></h5>
          <hr>
          <h4><strong>Descripcion del proyecto</strong></h4>
          <p><em><?php echo $pro->get_property("description"); ?></em></p>
          
         
        </div>
    </section>
    <hr><hr>
