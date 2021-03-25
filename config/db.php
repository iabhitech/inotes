<?php
  /* This file has config related to SQL Database */
  $db_host = "localhost";
  $user_name = "root";
  $password = "";
  $db_name = "inotes";

	$conn = mysqli_connect($db_host, $user_name, $password, $db_name);
  $GLOBALS['db'] = $conn;
  if (!$conn) {
    echo "<h1>Service Unavailable!</h2>";
    echo "<p>Something bad happened. Please come back later when we fixed that problem. Thanks.</p>";
    exit;
  }
?>