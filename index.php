<?php
        include_once 'header.php';
        include_once 'functions/get_images.php';
        
        $images = get_images($slide_thumbs_location);
        $num_elements = count($images);
?>
      
    <section id="slideshow" class="container">

      <div class="bg-faded p-4 my-4">
        <!-- Image Carousel -->
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
          <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <?php
           
                for($i = 1; $i < $num_elements; $i++)
                {?>
                        <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $i;?>"></li>
                 <?php
                }
                ?>
          </ol>
          <div class="carousel-inner" role="listbox">
              
              <?php
              foreach ($images as $it => $va)
                {
                  if($it == 0){?>
                    <div class="carousel-item active">
                        <img class="d-block img-fluid w-100" src="<?php echo $slide_thumbs_location . '/' . $va;?>" alt="">
                    </div>
                    
              
                 <?php   
                }
                else 
                {?>
                   <div class="carousel-item">
                        <img class="d-block img-fluid w-100" src="<?php echo $slide_thumbs_location . '/' . $va;?>" alt="">
                    </div> 
                <?php
                }
                }
                ?>       
          </div>
            
          <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
        
        <!-- Welcome Message -->
        <div class="text-center mt-4">
          <div class="text-heading text-muted text-lg">Become a client of</div>
          <h2 class="my-2">Capiro´s Windows</h1>
          <a class="text-heading text-muted text-lg" href="contact.php">Request a free estimate now</a>
          </div>
        
      </div>
        
      </section>

      <section id="who_we_are" class="bg-faded p-4 my-4">
        <hr class="divider">
        <h2 class="text-center text-lg text-uppercase my-0"><strong>Who we are</strong></h2>
        
        <hr class="divider">
        
        <p class="home text-center">Capiro’s Windows Corporation is a growing company who deliver a customize service,
            focused on the quality, safety and satisfaction of our clients expectation.</p>
     
      </section>

      <section id="who_we_are" class="bg-faded p-4 my-4">
        <hr class="divider">
        <h2 class="text-center text-lg text-uppercase my-0"><strong>Why to hire us?</strong></h2>
        
        <hr class="divider">
        
        <p class="home text-center">We offer high quality products and a fast and efficient service, focused on solving client demands. 
                        We make homes more secure, ready to face natural disasters. 
                        Your home will be beautiful and made to last long, which increase the property value.
                        Say bye to high electricity bills.
                        Reduces the frequency of common repairs.
                        You may contact us for free estimates.
        </p>
        
        
      </section>


    
 
   <?php
   
   include_once 'footer.php';
   
 