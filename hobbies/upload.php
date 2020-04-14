<?php

  // Step 1: Start the session IF it hasn't already been started
  session_start();

  // Step 2: If the user isn't logged in, redirect them with an error message
  //  back to the home page
  if (!isset($_SESSION['user'])) {
    $_SESSION['errors'][] = "You must log in";
    header('Location: ./index.php');
    exit;
  }

  // Below is the error handling function that will capture all errors and
  // pass them as an exception
  set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    $date = date("d-m-Y H:i:s");

    $error_msg_format = "[{$date}][ERROR]: {$errstr}\nFILE: {$errfile}\nLINE: {$errline}\n";

    // Step 3: Throw a new exception, passing the $error_msg_format as the argument
    throw new Exception($error_msg_format);
  });


  // Step 4: Verify the file is a JSON file (use any strategy you wish)
  //  a) Add an error if it isn't a JSON file
  //  b) Redirect back to the hobbies/index.php page if there's an error
  @json_decode($data);
  if(!(json_last_error() === JSON_ERROR_NONE)){
    $errors[]="File is not JSON format";
    header('Location: ./index.php');
  }

  // Step 5: Wrap the following code (using a try/catch block)
  //  NOTE: Pay special attention to the comments.
  //    If you find yourself adding new curly braces you're doing it wrong!
  try{ // NOTE: This curly brace is for the try block
    $file_location = "./uploads/{$_FILES['data_file']['name']}";
    move_uploaded_file($_FILES['data_file']['tmp_name'], $file_location);

    $content = file_get_contents($file_location);
    $data = json_decode($content);

    include('../_connect.php');
    $conn = connect();

    $sql = "INSERT INTO hobbies (
      name,
      description,
      frequency_per_week,
      user_id
    ) VALUES (
      :name,
      :description,
      :frequency_per_week,
      {$_SESSION['user']['id']}
    )";

    $stmt = $conn->prepare($sql);

    foreach ($data as $datum) {
      $errors = [];
      foreach (['name', 'frequency_per_week'] as $field) {
        $human_field = str_replace("_", " ", $field);
        if (empty($datum->$field)) {
          $errors[] = "You cannot leave the {$human_field} blank.";
        }
      }
      
      foreach (['name', 'description'] as $field) {
        $datum->$field = filter_var($datum->$field, FILTER_SANITIZE_STRING);
      }

      $datum->frequency_per_week = filter_var($datum->frequency_per_week, FILTER_SANITIZE_NUMBER_FLOAT);

      if (count($errors)  > 0) {
        throw new Exception(implode("\n", $errors));
      }

      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':name', $datum->name, PDO::PARAM_STR);
      $stmt->bindParam(':description', $datum->description, PDO::PARAM_STR);
      $stmt->bindParam(':frequency_per_week', $datum->frequency_per_week, PDO::PARAM_INT);
      $stmt->execute();
      
      if ($stmt->errorCode() !== "00000") {
        // Step 6: Throw a new exception
        //  a) Pass the error info message (at index 2)
        //  HINT: https://www.php.net/manual/en/pdo.errorinfo.php
        //  HINT: You CANNOT pass an array. It has to be a string ;)
        throw new Exception($stmt->errorInfo()[2]);
      }
    }

    $_SESSION['successes'][] = "Your hobbies were created successfully.";
    header('Location: ./');
    exit;
  }  catch{ // These curly braces are for the catch block
    // Step 7: Record the error message (provided by the Exception)
    //  in the error log file using the correct function
    //  https://www.php.net/manual/en/function.error-log
    //  HINT: You want message type 3
    
    
    $_SESSION['errors'][] = "There was an error in uploading the file";
    header('Location: ./');
    exit;
  }