<?php
require_once('config/db.php');

function saveNote($title, $body, $label=null, $uid){
  $sql = "INSERT INTO `notes` (`title`, `body`, `label`, `timestamp`,`userid`) VALUES ('$title', '$body', '$label', current_timestamp(), '$uid')";
  return mysqli_query($GLOBALS['db'], $sql);
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $title = $_POST['note_title'];
  $body = $_POST['note_body'];
  $label = $_POST['note_label'];
  session_start();
  $uid = $_SESSION['user'];

  if(!$title || !$body){
    $_SESSION['error'] = "All Fields are required";
  }
  if(saveNote($title, $body, $label, $uid)){
    $_SESSION['msg'] = "Saved Successfully!";
  }
    header('Location:index.php');

  // var_dump($title,$body,$label);
}
