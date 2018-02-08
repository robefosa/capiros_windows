<?php
    session_start();
    
    include_once 'classes/login.php';
    include_once '../data/common_data.php';
    
    $error_msg = FALSE;
    
    try {
       $log = new login($read_only_user_name, $read_only_user_pass, $data_base_name, "login");  
    } catch (Exception $ex) {
         $error_msg = $ex->getMessage();   
    }
    
    if($_POST && $_POST['send'])
    {
        if($log->check_login($_POST['user'], $_POST['password']))
        {
            $_SESSION['ok'] = TRUE;
            header("Location: dashboard/index.php");
        }
        else {
            $error_msg = $log->get_error();
        }
    }
    
    
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin Login</title>

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">
    
     <!-- Custom CSS -->
     <link href="../css/business-casual.css" rel="stylesheet">


    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
    <script type="text/javascript">
        function check()
        {
            if(document.forms['login']['user'].value == "")
            {
                document.getElementById('error').style.display = 'block';
                return false;
            }
            else
                {
                    if(document.forms['login']['password'].value == "")
                    {
                        document.getElementById('passError').style.display = 'block';
                        return false;
                    }
                    else{
                        return true;
                    }
                  
                }
        }
    </script>
    <?php ?>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        
                        <form id="login" name="login" method="post" onsubmit="return check();">
                           
                          <?php 
                                if($error_msg)
                                {
                                    echo '<p class="alarm_text">' . $error_msg . '</p>';   
                                }
                          ?>
                            <p id="error" name="error" class="alarm_text" style="display: none;">You should provide an user name</p>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="User Name" id="user" name="user" value="" autofocus>
                                    </div>
                            
                            <p id="passError" class="alarm_text" style="display: none;">You should provide a pass</p>    
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" value="" id="password" name="password" type="password" value="">
                                </div>
                                
                                <div>
                                    <a href="forgot_pass.php">I forgot my password</a>
                                </div>
                               
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                </div>
                            <input type="submit" id="send" name="send" class="btn btn-lg btn-success btn-block" value="Login">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

</body>

</html>
