 <footer class="bg-faded text-center py-5">
      <div class="container">
          <div>
              <a href="https://www.facebook.com/Capiros-Windows-corp-1810905995901795/"> 
                  <img class="social_media" src="img/facebook.png" alt="Facebook profile">
              </a>
              <a href="https://www.instagram.com/capiros_windows.corp/">
                  <img class="social_media" src="img/instagram.png" alt="Instagram profile">
              </a>
                
          </div>
          <p id="copyright" class="m-0">Copyright &copy; Capiro´s Windows Corp. 2017</p>
      </div>
    </footer>

<!--Check for temporal files to erase them-->
    <?php
    if ($temp_files && is_array($temp_files))
                {                    
                    $_SESSION['temp'] = $temp_files;
                }
                                
    ?>
        
    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/popper/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

  </body>

</html>
