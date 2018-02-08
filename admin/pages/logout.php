<?php

if(isset($_COOKIE[session_name()]))
{
    setcookie(session_name(), "", time()-86000, "/");
    session_destroy();
    header("Location: ../index.php");
}
