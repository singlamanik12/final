<?php

  /*
    You don't need to change anything on this page. It has been provided for
    you already.

    If you want to add helper functions to this page, feel free to do that
  */
    
  function connect () {
    $host = "comp-1006.cq2sofwg3vlf.us-east-1.rds.amazonaws.com";
    $user = "final_attendees";
    $pass = "g00dLuc8";
    $db = "final";
    
    // Create the connection
    $conn = new PDO("mysql:host={$host};dbname={$db}", $user, $pass);

    return $conn;
  }