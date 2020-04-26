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

<!DOCTYPE html>
<html lang="en">
  <!-- prettier-ignore -->
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
    <script src="https://kit.fontawesome.com/7f1bac7050.js" crossorigin="anonymous"></script>
    <title>PHP todo list – Ty Mick</title>
  </head>

  <body style="background-color: #dee2e6;">
    <div class="container">
      <div class="card my-4">
        <div class="card-body">
          <!-- Title -->
          <h1 class="mb-4">PHP todo list</h1>

          <!-- Input form -->
          <form class="d-flex mb-4" method="post" action="">
            <input
              type="text"
              class="form-control mr-2"
              name="new-task"
              aria-label="New task"
            />
            <button type="submit" class="btn btn-primary">Add</button>
          </form>

          <!-- Task list -->
          <ul class="list-group mb-4">
<?php
// List incomplete tasks first, then in decending order by date
$statement = $pdo->prepare("SELECT * FROM Todos ORDER BY Complete ASC, Completed DESC, Created DESC");
$statement->execute();

foreach ($statement as $todo) {
  $task = $todo["Task"];
  $complete = $todo["Complete"];
?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <!-- Task -->
              <?= $complete ? '<del class="text-secondary">' : ''; ?>
                <?= $task; ?>
              <?= $complete ? '</del>' : ''; ?>

              <!-- Complete/delete buttons -->
              <div class="btn-group" role="group" aria-label="Task actions">
                <button type="button" class="btn btn-success<?= $complete ? ' active' : ''; ?>"<?= $complete ? ' aria-pressed="true"' : ''; ?> aria-label="Complete">
                  <i class="fas fa-check fa-fw"></i>
                </button>
                <button type="button" class="btn btn-danger" aria-label="Delete">
                  <i class="fas fa-trash-alt fa-fw"></i>
                </button>
              </div>
            </li>
<?php
}
?>
          </ul>

          <!-- Delete all tasks button -->
          <button type="button" class="btn btn-danger px-5 d-block mx-auto">
            DELETE ALL TASKS
          </button>
        </div>
      </div>
    </div>
  </body>
</html>
