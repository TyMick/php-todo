<?php

// Connect to the database
$pdo = new \PDO("sqlite:db/sqlite.db");

/// User actions
// Create todo

// Complete todo

// Delete todo

// Delete all todos
?>

<!doctype html>
<html lang="en">
  <!-- prettier-ignore -->
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
    <title>PHP todo list – Ty Mick</title>
  </head>

  <body style="background-color: #dee2e6;">
    <div class="container">
      <div class="card my-5">
        <div class="card-body">
          <h1 class="mb-4">PHP todo list</h1>

          <!-- Input form -->
          <form class="form-inline" method="post">
            <input type="text" class="form-control mb-2 mr-sm-2" id="task" aria-label="Task" />
            <button type="submit" class="btn btn-primary mb-2">Add</button>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
