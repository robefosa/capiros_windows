<?php 
session_start();
if($_SESSION && isset($_SESSION['temp']))
{
    $temp = $_SESSION['temp'];
    foreach ($temp as $value)
    {
        unlink($value);
    }
    unset($_SESSION['temp']);
}
include_once 'data/common_data.php';

$actual = ucfirst(pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME));
$title = "";

switch ($actual){
    case "Index":
        $title = "Home";
        break;
    case "About":
        $title = "About";
        break;
    case "Gallery":
        $title = "Proyects Gallery";
        break;
    case "Proyect_view":
        $title = "Proyect Images";
        break;
    case "About":
        $title = "About";
        break;
    case "Contact":
        $title = "Contact";
        break;
    default :
        $title = "Capiros Windows";
   }

?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="windows installations, doors, impact windows">
    <meta name="author" content="Osvaldo Santos">
    <link rel="icon" href="<?php echo $server_base_url . "/img/logo.jpg";?>">

    <title><?php echo $title;?></title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Slab:100,300,400,600,700,100italic,300italic,400italic,600italic,700italic" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="css/business-casual.css" rel="stylesheet">

  </head>

  <body class="container">
      
      <!-- Check for temporal files and erase them-->
      <?php
      if ($_POST && $_POST['temp_files'])
      {
          
          foreach ($_POST['temp_files'] as $value)
          {
              echo $value;
          }
      }
      ?>
<header>
    <div class="tagline-upper text-center text-heading text-shadow mt-5 d-none d-lg-block">Capiro´s Windows Corp.</div>
    <div class="tagline-lower text-center text-expanded text-shadow text-uppercase mb-5 d-none d-lg-block">Windows and doors installations.</div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-faded py-lg-4">
      <div class="container">
        <a class="navbar-brand text-uppercase text-expanded font-weight-bold d-lg-none" href="#">Capiro´s Windows</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav mx-auto">
              <li class="nav-item px-lg-4 <?php if ('index.php' == $actual){echo 'active';}?>">
                <a class="nav-link text-uppercase text-expanded" href="index.php">Home
                <?php 
                if ('index.php' == $actual){
                ?>
                    <span class="sr-only">(current)</span>
                <?php 
                }
                ?>    
              </a>
            </li>
            <li class="nav-item px-lg-4 <?php if ('about.php' == $actual){echo 'active';}?>">
                <a class="nav-link text-uppercase text-expanded" href="about.php">About
                <?php 
                if ('about.php' == $actual){
                ?>
                    <span class="sr-only">(current)</span>
                <?php 
                }
                ?>    
                </a>
            </li>
            <li class="nav-item px-lg-4 <?php if ('gallery.php' == $actual){echo 'active';}?>">
                <a class="nav-link text-uppercase text-expanded" href="gallery.php">Proyects Gallery
                <?php 
                if ('gallery.php' == $actual){
                ?>
                    <span class="sr-only">(current)</span>
                <?php 
                }
                ?>    
                </a>
            </li>
            <li class="nav-item px-lg-4 <?php if ('contact.php' == $actual){echo 'active';}?>">
                <a class="nav-link text-uppercase text-expanded" href="contact.php">Contact
                <?php 
                if ('contact.php' == $actual){
                ?>
                    <span class="sr-only">(current)</span>
                <?php 
                }
                ?>    
                </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
</header>



