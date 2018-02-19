<?php
include_once '../../data/common_data.php';
include_once '../classes/login.php';

if(!$_POST)
{
    ?>
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please provide your email</h3>
                    </div>
                    <div id="wrap" class="panel-body">
                        
                        <form id="email-submit" name="email-submit" method="post">
                           
                                    <div class="form-group">
                                        <input class="form-control" placeholder="email" id="email" name="email" value="" type="email" autofocus required="">
                                    </div>
                            <input type="submit" id="recover-password" name="recover-password" class="btn btn-lg btn-success btn-block" value="Recover Password">
                            
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
   
    <?php
}
 else 
 {
    
     //connect to db or show error
     try {
        $log = new login($total_control_user_name, $total_control_user_pass, $data_base_name, "login");
        
    } catch (Exception $ex) {
    ?>
       
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <h2 class="text-center text-lg text-uppercase my-0 alarm_text">
                    <strong class="alarm_text">Sorry, we are experimenting some troubles. Tray again later.</strong></h2>
                </div>
            </div>
        </div>
   
    <?php
    }
    
    //check for email in db
        $email = $log->check_if_email_exist($_POST['email']);
        
        if($email)
        {
            $log->send_pass_recovery_email($email, $password_reset_script);
        }
        else {
            ?>
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="login-panel panel panel-default">
                            <h2 class="text-center text-lg text-uppercase my-0 alarm_text">
                            <strong class="alarm_text">If you have an acount with this email registered you´ll 
                                receive a link to reset your password.</strong></h2>
                        </div>
                    </div>
                </div>
            
            <?php
        }
}
    
     
