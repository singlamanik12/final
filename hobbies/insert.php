<?php

  /*
    You don't need to change anything on this page. It has been provided for
    you already.
  */

  if (session_status() === PHP_SESSION_NONE) session_start();
  
  if (!isset($_SESSION['user'])) {
    $_SESSION['errors'][] = "You must login first.";
    header('Location: ../');
    exit;
  }

  $errors = [];
  foreach (['name', 'frequency_per_week'] as $field) {
    $human_field = str_replace("_", " ", $field);
    if (empty($_POST[$field])) {
      $errors[] = "You cannot leave the {$human_field} blank.";
    }
  }
  
  foreach (['name', 'description'] as $field) {
    $_POST[$field] = filter_var($_POST[$field], FILTER_SANITIZE_STRING);
  }

  $_POST['frequency_per_week'] = filter_var($_POST['frequency_per_week'], FILTER_SANITIZE_NUMBER_FLOAT);

  if (count($errors)  > 0) {
    // Add the current form values to the $_SESSION
    $_SESSION['form_values'] = $_POST;
    
    // Store the errors
    $_SESSION['errors'] = $errors;
    
    // Redirect back to the form and exit
    header('Location: ./index.php');
    exit;
  }

  require('../_connect.php');
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
  $stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
  $stmt->bindParam(':description', $_POST['description'], PDO::PARAM_STR);
  $stmt->bindParam(':frequency_per_week', $_POST['frequency_per_week'], PDO::PARAM_INT);
  $stmt->execute();
  
  if ($stmt->errorCode() !== "00000") {
    // Add the error message to the errors session array
    error_log($stmt->errorInfo()[2] . "\n\n", 3, './error.log');
    $_SESSION['errors'][] = "There was an error during registration.";
    $_SESSION['form_values'] = $_POST;
  } else {
    // Add the success message to the successes session array
    $_SESSION['successes'][] = "You have been registered successfully.";
    header('Location: ./');
    exit;
  }
  
  header('Location: ./');
  exit;