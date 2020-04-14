<?php

  /*
    You don't need to change anything on this page. It has been provided for
    you already.
  */

  if (session_status() === PHP_SESSION_NONE) session_start();
  $_messages = [
    'danger' => ($_SESSION['errors'] ?? null),
    'success' => ($_SESSION['successes'] ?? null)
  ];

  unset($_SESSION['errors']);
  unset($_SESSION['successes']);

?>

<?php foreach ($_messages as $type => $messages): ?>
  <?php if ($messages && count($messages) > 0): ?>
    <div class="alert alert-<?= $type ?>">
      <?php foreach ($messages as $message): ?>
          <?= $message ?><br>
      <?php endforeach ?>
    </div>
  <?php endif ?>
<?php endforeach ?>