<?php

  $dsn = 'mysql:host=localhost;dbname=seniorDesign';
  $username = 'root';
  $password = 'AmazonESPortal2016';
  $options = array(
                   PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                   );
  
  try {
    $dbh = new PDO($dsn, $username, $password);
    //echo "Connected";
  } catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    
  }
  
?>
