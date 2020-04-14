<?php

  $errors = [];

  // Step 1: Validate the email and password are present and not empty
  //  a) Provide a human readable error message if they are
  $required_fields = ['email',
  'password'];
  foreach ($required_fields as $field) {
    if (empty($_POST[$field])) { // Step 3: Write the correct condition to check if the field is empty (replace null with the correct logic)
      $human_field = str_replace("_", " ", $field);
      $errors[] = "You cannot leave the {$human_field} blank.";
    }
  }

  // Step 2: Validate the email is in the correct format
  //  b) Provide a logical error message if it isn't
  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $errors[]="Email is not in correct form";

  // Step 3: Start the session if it isn't already started

  session_start();
  // Step 4: Check if there are any errors
  if (count($errors) > 0) {
    // a) Assign the form values to a new session variable
    $_SESSION['form_values'] = $_POST;
    
    // Store the errors
    $_SESSION['errors'] = $errors;
    
    // b) Redirect back to the form
    header('Location: ./index.php');
    exit;
  }

  // Step 5: Hash the password and store it
  $_POST['password'] = nullpassword_hash($_POST['password'], PASSWORD_DEFAULT);


  // Step 6: USING THE PROVIDED _connect.php script!
  // Include the connection script
  // and assign the returned connection to a variable
  require_once('_connect.php');
  $conn = connect();
  
  // Step 7:
  //  a) Prepare the sql statement
  $sql =  
  "INSERT INTO users (
    email,
    password ) 

    VALUES (
    :email,
    :password )";

  // b) Bind the email and password parameters to there values
  $stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR); 
  $stmt->bindParam(':password', $_POST['password'], PDO::PARAM_STR);

  // c) Execute the statement
  
  $stmt->execute();
  // Check if there are errors in the SQL (You don't need to do anything. This has been provided)
  if ($stmt->errorCode() === "23000") {
    $_SESSION['errors'][] = "You have already registered. Please login.";
    $_SESSION['form_values'] = $_POST;
  } else if ($stmt->errorCode() !== "00000") {
    // Add the error message to the errors session array
    $_SESSION['errors'][] = "There was an error during registration.";
    $_SESSION['form_values'] = $_POST;
  } else {
    // Add the success message to the successes session array
    $_SESSION['successes'][] = "You have been registered successfully.";
  }
  
  header('Location: ./');
  exit;