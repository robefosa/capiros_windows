<?php

include_once 'data/common_data.php';
include_once 'functions/mail.php';

$email_error = FALSE;
$expected = array("customer" => "", "e_mail" => "", "phone" => "", "message" => "");

if(!isset($fields_missing) || !$fields_missing)
{
   if (isset($_POST['send'])) 
    {
        if(empty($_POST['customer']) || empty($_POST['e_mail']))
        {
            $fields_missing = TRUE;
        }      
        elseif (!empty($_POST['e_mail']))
        {           
            $validemail = filter_input(INPUT_POST, 'e_mail', FILTER_VALIDATE_EMAIL);  
            
            if($validemail == FALSE || $validemail == NULL)
            {                
                $email_error = TRUE;
            }
            else 
            {   
                foreach ($expected as $key => $value) 
                    {
                        $expected[$key] = $_POST[$key];
                    }
                    
                //Subjet
                $sub = "Contacto desde Capiros Windows"; 
                
                // Aditional headers    
                $headers .= "Cc: ";
                foreach ($owners_emails_address as $item => $value)
                {
                  $headers .= $value . ", ";  
                }
                $headers .= "\r\n";    
                
                $mail_sent = array();
                
                foreach ($owners_emails_address as $address)
                {
                    $mail_sent[] = send_contact_email($expected, $address, $sub, $headers); 
                    
                    foreach ($mail_sent as $value)
                    {
                        if($value == TRUE)
                        {
                            $ok = $value;
                            break;
                        }
                    }
                }
                
                if ($ok)
                {
                    header('Location:' . $server_base_url .'/email_confirmation.php');
                }
                else 
                    {
                        header('Location:' . $server_base_url .'/email_confirmation_fail.php');
                    }
            }
            
        }
    }
    
} 
?>

<?php
        include_once 'header.php';

?>
<section class="container">

      <div class="bg-faded p-4 my-4">
          
        <hr class="divider">
        
        <h2 class="text-center text-lg text-uppercase my-0">Contact <strong>Capiros Windows</strong></h2>
        
        <hr class="divider">
        
        <div class="row">
            
            <div id="contact_info" class="col-lg-4">
            <h5 class="mb-0">Phone:</h5>
            <div class="mb-4">786 374 9885</div>
            <h5 class="mb-0">Email:</h5>
            <div id="e_mail" class="mb-4">
                <a href="mailto:<?php echo $owners_emails_address[0];?>"><?php echo $owners_emails_address[0];?></a>
            </div>
            <h5 class="mb-0">Address:</h5>
            <div class="mb-4 col-sm-12">
              6793 SW 27 Court
              <br>
              Miramar, FL 33023
            </div>
          </div>
        </div>
      </div>
</section>

        <section id="contact_form" class="bg-faded p-4 my-4">
            
        <hr class="divider">
        
        <h2 class="text-center text-lg text-uppercase my-0">Contact <strong>Form</strong></h2>
       
        <hr class="divider">
        
        <form id="contact" name="contact" method="post" action="">
          
            <div class="row">
            
              <div class="form-group col-lg-4">
              <label class="text-heading">Name*</label>
              <input id="customer" name="customer" type="text" 
                     class="form-control <?php if(isset($fields_missing) && $fields_missing && !empty($_POST['e_mail'])){?>alarm<?php }?>"
                     <?php if (isset($fields_missing) && $fields_missing && !empty($_POST['customer'])) {echo 'value="' . htmlentities($_POST['customer'], ENT_COMPAT, 'UTF-8') . '"';} ?>> 
            </div>
              
            <div class="form-group col-lg-4">
                
                <label class="text-heading"> 
                    <?php if($email_error) { ?><strong class="alarm_text">Please provide a valid e-mail address</strong> 
                        <?php }else{ ?>Email Address*<?php } ?></label>
                
              <input id="e_mail" name="e_mail" type="email" 
                     class="form-control 
                  <?php if((isset($fields_missing) && $fields_missing && !empty($_POST['customer'])) || $email_error){?>alarm<?php }?>"
                  <?php if (isset($fields_missing) && $fields_missing && !empty($_POST['e_mail'])) {echo 'value="' . htmlentities($_POST['e_mail'], ENT_COMPAT, 'UTF-8') . '"';} ?>>
            </div>
              
            <div class="form-group col-lg-4">
              <label class="text-heading">Phone Number</label>
              <input id="phone" name="phone" type="tel" class="form-control" 
                     <?php if (isset($fields_missing) && $fields_missing && !empty($_POST['phone'])) {echo 'value="' . htmlentities($_POST['phone'], ENT_COMPAT, 'UTF-8') . '"';} ?>>
            </div>
              
            <div class="clearfix"></div>
            <div class="form-group col-lg-12">
              <label class="text-heading">Message</label>
              <textarea id="message" name="message"class="form-control" rows="6">
                 <?php if (isset($fields_missing) && $fields_missing && !empty($_POST['message'])) {echo htmlentities($_POST['message'], ENT_COMPAT, 'UTF-8');} ?>
              </textarea>
            </div>
            
            <div class="form-group col-lg-12">
                <button type="submit" id="send" name="send" class="btn btn-secondary">Send</button>
            </div>
            
            <?php if(isset($fields_missing) && $fields_missing){
                $fields_missing = FALSE;
                ?>
                <div class="form-group col-lg-12 req_camps">
                <label class="text-heading">Please fill all required fields</label>
                </div>
                                        
            <?php } else {?>
                <div class="form-group col-lg-12 req_camps">
                <label class="text-heading">Fields marked with * are required</label>
                </div>
            <?php } ?>
 
          </div>
        </form>
        </section>

        <!-- /.container -->

    

    <!-- Zoom when clicked function for Google Map -->
    <script>
      $('.map-container').click(function () {
        $(this).find('iframe').addClass('clicked')
      }).mouseleave(function () {
        $(this).find('iframe').removeClass('clicked')
      });
    </script>

  <?php
   
   include_once 'footer.php';
   
   ?>