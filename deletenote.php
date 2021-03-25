<?php
require_once('config/db.php');
session_start();
if(!isset($_SESSION['user'])){
  header("Location: login.php");
}
function delete($id){
  $sql = "DELETE FROM `notes` WHERE `notes`.`id` = $id";
  return mysqli_query($GLOBALS['db'], $sql);
}
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  if(!isset($_POST['id'])){
    header("Location: login.php");
  }
  if(delete($_POST['id'])){
    header("Location: index.php");
  }
  echo "Some Error Occurred!";
}
?>