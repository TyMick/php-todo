<?php

// Connect to the database
try {
  $pdo = new PDO("sqlite:db/todos.db");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

/// User actions
// Create new todo
if (isset($_POST["new-task"])) {
  try {
    $task = $_POST["new-task"];
    $statement = $pdo->prepare(
      "INSERT INTO Todos (Task, Complete, Created) VALUES (:task, FALSE, strftime('%s', 'now'))"
    );
    $statement->execute([":task" => $task]);
  } catch (PDOException $e) {
    echo "Todo creation failed: " . $e->getMessage()();
  }
}

// Complete todo

// Delete one todo

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
      <div class="card my-4">
        <div class="card-body">
          <h1 class="mb-4">PHP todo list</h1>

          <!-- Input form -->
          <form class="form-inline" method="post" action="">
            <input type="text" class="form-control mb-2 mr-sm-2" name="new-task" aria-label="New task" />
            <button type="submit" class="btn btn-primary mb-2">Add</button>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
