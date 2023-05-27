


<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

  /* DATABASE CONNECTION*/


  $db['db_host'] = 'localhost';
  $db['db_user'] = 'root';
  $db['db_pass'] = '';
  $db['db_name'] = 'excel_data';
  
  foreach($db as $key => $value) {
      define(strtoupper($key), $value);
  }
  
  global $connection;

  $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);


  if (!$connection) {
      die("Cannot Establish A Secure Connection To The Host Server At The Moment!");
  }
  
 
  $dbHost = 'localhost:3308';
  $dbName = 'excel_data';
  $dbCharset = 'utf8';
  $dbUser = 'root';
  $dbPass = '';
  
  try {
      $db = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=$dbCharset", $dbUser, $dbPass);
      // Additional configuration options if needed
  } catch (PDOException $e) {
      die('Cannot Establish A Secure Connection To The Host Server At The Moment!');
  }

      /*DATABASE CONNECTION */



?>