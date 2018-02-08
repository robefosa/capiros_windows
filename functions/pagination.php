<?php

/* 
 * this function shows pagination with especific ccs style storaged in business-casual.css 
 * $script_path should include at least one variable passed to the $_GET array 
 */


function pagination($items_number, $items_by_page, $actual_page, $script_path)
{
    //get number of pages
    $modulo = $items_number % $items_by_page;
    if($modulo == 0)
    {
        $num_pages = $items_number / $items_by_page;
    }
    else 
    {
        $num_pages = (int) floor($items_number / $items_by_page) + 1;
    }

    //if less than 3 items do not show any pagination
    if($items_number <= $items_by_page){}

    //firts page (do not show Previus arrow)
    elseif($actual_page == 0)
    {
        ?><ul id="pageul"class="pagination justify-content-center"><?php

                if($num_pages > 3){$top = 2;}
                else{$top = $num_pages - 1;}

                for($i = 0; $i <= $top; $i++ )
                {?>
                    <li class="page-item">
                        <a class="page-link <?php if ($i == $actual_page){echo 'actual';}?>" href=<?php echo '"' . $script_path . '&page=' . $i . '"';?>><?php echo ($i + 1); ?></a>
                    </li>
                    <?php
                }  
            ?>
            <li class="page-item">
                <a class="page-link" href=<?php echo '"' . $script_path . '&page='. ($actual_page + 1) . '"';?> aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
              </a>
            </li>
          </ul>

        </div>
    <br><br><br><br>

    <?php
     }
     //last page (do not show Next arrow)
     elseif ($actual_page == ($num_pages - 1)) 
    {
          ?>
           <ul id="pageul"class="pagination justify-content-center">
            <li class="page-item">
                <a class="page-link" href=<?php echo '"' . $script_path . '&page='. ($actual_page - 1) . '"';?> aria-label="Next">
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
                        <a class="page-link <?php if ($i == $num_pages){echo 'actual';}?>" href=<?php echo '"' . $script_path . '&page=' . ($i - 1) . '"';?>><?php echo $i; ?></a>
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
                <a class="page-link" href=<?php echo '"' . $script_path . '&page='. ($actual_page - 1) . '"';?> aria-label="Next">
                    <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Previus</span>
              </a>
            </li>

            <?php 

               for($i = $actual_page - 1; $i <= ($actual_page + 1); $i++ )
                {?>
                    <li class="page-item">
                        <a class="page-link <?php if ($i == $actual_page){echo 'actual';}?>" href=<?php echo '"' . $script_path . '&page=' . $i . '"';?>><?php echo ($i + 1); ?></a>
                    </li>
                    <?php
                }  

            ?>

            <li class="page-item">
                <a class="page-link" href=<?php echo '"' . $script_path . '&page='. ($actual_page + 1) . '"';?> aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
              </a>
            </li>
          </ul>
        <br><br><br><br>
<?php
    }}