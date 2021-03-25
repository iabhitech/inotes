<?php
session_start();
unset($_SESSION['user']);
if(session_destroy())
  header('Location: login.php');
else{
  echo "Unable to Logout!";
}
?>