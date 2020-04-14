<?php

  // Step 1: Start the session IF it hasn't already been started
  session_start();
  
  // Step 2: USING THE PROVIDED _connect.php script!
  // Include the connection script
  // and assign the returned connection to a variable
  require('./_connect.php');
  $conn = connect();
  
  // Step 3: Using bound parameters, write your SQL statement
  // selecting the user by their email
  // HINT: You're using PDO so make sure your code reflects that!
  $sql = "SELECT * FROM users WHERE email = :email";
  
  // Step 4: Validate and sanitize the email
  
  $email=filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  // Step 5:
  //  a) Prepare the sql statement
  
  $stmt = $conn->prepare($sql);
  // b) Bind the email parameter to the value
  $stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
  // c) Execute the statement
  $stmt->execute();

  // Step 6: Fetch the user (HINT: you're only fetching ONE user)
  
  $user = $stmt->fetch();
  // Step 7: Verify you have a user and the password is correct
  if (!user) {
    $_SESSION['errors'][] = "You could not be authenticated. Check your email address or please register for an account.";

    // a) Assign the form values to a new session variable
    $_SESSION['form_values'] = $_POST;

    // b) Redirect back to the form
    header('Location: ./index.php');
  }

  // Step 8: Unset the user password
  unset($user['password']);

  // Step 9: Assign the user to a session variable
  $_SESSION['user'] = $user;

  // Redirect back to the form
  $_SESSION['successes'][] = "You have successfully logged in.";
  header('Location: ./hobbies');
  exit;