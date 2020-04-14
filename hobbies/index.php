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


  // Step 3: USING THE PROVIDED _connect.php script!
  // Include the connection script
  // and assign the returned connection to a variable
  require('./_connect.php');
  $conn = connect();

  // Step 4: Fetch ALL the hobbies for the LOGGED IN USER
  $hobbies = "select * from hobbies";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Hobbies</title>
  </head>

  <body>
    <?php include_once('../notification.php') ?>


    <div class="container">
      <div class="row my-5">
        <div class="col">
          <header>
            <h1 class="display-4">My Hobbies</h1>
          </header>

          <hr>
          
          <a href="../logout.php" class="btn btn-danger my-3">Logout</a>
          <?php if ($hobbies): ?>
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Hobby</th>
                    <th>Description</th>
                    <th>Frequency</th>
                  </tr>
                </thead>

                <?php foreach ($hobbies as $hobby): ?>
                  <tbody>
                    <tr>
                      <td><?= $hobby['name'] ?></td>
                      <td><?= $hobby['description'] ?></td>
                      <td><?= $hobby['frequency_per_week'] ?></td>
                    </tr>
                  </tbody>
                <?php endforeach ?>
              </table>
          <?php else: ?>
            <div class="alert alert-warning">
              <p>Still waiting on some hobbies to be populated into the database...</p>
            </div>
          <?php endif ?>
        </div>

        <div class="col border-left">
          <header>
            <h2 class="display-4">Create New Hobby</h2>
          </header>

          <hr>

          <form action="./insert.php" method="post">
            <div class="form-group">
              <label for="name">Name:</label>
              <input type="text" class="form-control" name="name">
            </div>

            <div class="form-group">
              <label for="frequency_per_week">Frequency:</label>
              <input type="number" class="form-control" name="frequency_per_week" step="1">
            </div>

            <div class="form-group">
              <label for="description">Description:</label>
              <textarea name="description" cols="30" rows="10" class="form-control"></textarea>
            </div>

            <div class="form-group">
              <button class="btn btn-primary" type="submit">Create Hobby</button>
            </div>
          </form>

          <hr class="my-3">

          <header>
            <h2 class="display-4">OR Upload a Bunch of Hobbies</h2>
          </header>

          <hr>

          <!-- Step 5: Add the correct encoding type for file uploading -->
          <form action="./upload.php" method="post">
            <div class="form-group">
              <label>Data file format:</label>
            </div>

            <div class="alert alert-info">
              <pre>
                <code>
                  [
                    {
                      "name": "thing",
                      "description": "thing good",
                      "frequency_per_week": 1
                    },
                    {
                      "name": "thing 2",
                      "description": "thing 2 good",
                      "frequency_per_week": 5
                    }
                  ]
                </code>
              </pre>
            </div>

            <div class="input-group mb-3">
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="inputGroupFile02" name="data_file">
                <label class="custom-file-label" for="inputGroupFile02" aria-describedby="inputGroupFileAddon02">Choose a hobby data file</label>
              </div>

              <div class="input-group-append">
                <span class="input-group-text" id="inputGroupFileAddon02">Upload</span>
              </div>
            </div>

            <div class="form-group">
              <button class="btn btn-primary" type="submit">Create Hobby</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>