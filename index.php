<?php

  /*
    You don't need to change anything on this page. It has been provided for
    you already.
  */

  if (session_status() === PHP_SESSION_NONE) session_start();

  if (isset($_SESSION['user'])) {
    $_SESSION['successes'][] = "You are already logged in...";
    header('Location: ./hobbies');
    exit;
  }

  $form_values = $_SESSION['form_values'] ?? null;
  unset($_SESSION['form_values']);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Login or Register</title>
  </head>

  <body>
    <?php include_once('./notification.php') ?>
    <div class="container">
      <header class="my-5">
        <h1>Login or Register</h1>
      </header>

      <hr>

      <div class="row">
        <div class="col">
          <header>
            <h2 class="display-4">Login</h2>
          </header>

          <hr>

          <form action="authenticate.php" method="post">
            <div class="form-group">
              <label for="email">Email:</label>
              <input type="email" class="form-control" name="email">
            </div>

            <div class="form-group">
              <label for="password">Password:</label>
              <input type="password" class="form-control" name="password">
            </div>

            <div class="form-group">
              <button class="btn btn-primary" type="submit">Login</button>
            </div>
          </form>
        </div>

        <div class="col border-left">
          <header>
            <h2 class="display-4">Register</h2>
          </header>

          <hr>

          <form action="register.php" method="post">
            <div class="form-group">
              <label for="email">Email:</label>
              <input type="email" class="form-control" name="email">
            </div>

            <div class="form-group">
              <label for="email_confirmation">Email Confirmation:</label>
              <input type="email_confirmation" class="form-control" name="email_confirmation">
            </div>

            <div class="form-group">
              <label for="password">Password:</label>
              <input type="password" class="form-control" name="password">
            </div>

            <div class="form-group">
              <label for="password_confirmation">Password Confirmation:</label>
              <input type="password" class="form-control" name="password_confirmation">
            </div>

            <div class="form-group">
              <button class="btn btn-success" type="submit">Register</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>