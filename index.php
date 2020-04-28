<?php
// Connect to the database
try {
  $pdo = new PDO("sqlite:todos.db");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

  // Create table if necessary
  $pdo->query(
    "CREATE TABLE IF NOT EXISTS Todos (id INTEGER PRIMARY KEY, Task TEXT, Complete INTEGER, Created INTEGER, Completed INTEGER)"
  );
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

/// User actions
// Create new todo
if (isset($_POST["new-task"])) {
  try {
    $insert = $pdo->prepare(
      "INSERT INTO Todos (Task, Complete, Created) VALUES (:task, 0, strftime('%s', 'now'))"
    );
    $insert->execute([":task" => $_POST["new-task"]]);
  } catch (PDOException $e) {
    echo "Todo creation failed: " . $e->getMessage();
  }
}

// Complete todo
if (isset($_POST["complete"])) {
  try {
    $update = $pdo->prepare(
      "UPDATE Todos SET Complete = 1, Completed = strftime('%s', 'now') WHERE id = :id"
    );
    $update->execute([":id" => $_POST["id"]]);
  } catch (PDOException $e) {
    echo "Todo completion failed: " . $e->getMessage();
  }
}

// Uncomplete todo
if (isset($_POST["uncomplete"])) {
  try {
    $update = $pdo->prepare(
      "UPDATE Todos SET Complete = 0, Completed = NULL WHERE id = :id"
    );
    $update->execute([":id" => $_POST["id"]]);
  } catch (PDOException $e) {
    echo "Todo uncompletion failed: " . $e->getMessage();
  }
}

// Delete one todo
if (isset($_POST["delete-one"])) {
  try {
    $delete = $pdo->prepare("DELETE FROM Todos WHERE id = :id");
    $delete->execute([":id" => $_POST["id"]]);
  } catch (PDOException $e) {
    echo "Todo deletion failed: " . $e->getMessage();
  }
}

// Delete all todos
if (isset($_POST["delete-all"])) {
  try {
    $pdo->query("DELETE FROM Todos");
  } catch (PDOException $e) {
    echo "Todo deletion failed: " . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <!-- prettier-ignore -->
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
    <script src="https://kit.fontawesome.com/7f1bac7050.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Dawning+of+a+New+Day&display=swap" rel="stylesheet" />
    <title>PHP to-do list – Ty Mick</title>
  </head>

  <body
    class="d-flex flex-column position-absolute"
    style="min-height: 100%; width: 100%; background-color: #dee2e6;"
  >
    <div class="container-fluid" style="max-width: 720px;">
      <div class="card my-4">
        <div class="card-body">
          <!-- Title -->
          <h1 class="mb-4">PHP to-do list</h1>

          <!-- Input form -->
          <form class="d-flex mb-4" method="POST">
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
$select = $pdo->query(
  "SELECT * FROM Todos ORDER BY Complete ASC, Completed DESC, Created DESC"
);

// Find task count while looping
$count = 0;

// Loop through tasks
foreach ($select as $todo) {
  $count++;
  $id = $todo["id"];
  $task = $todo["Task"];
  $complete = $todo["Complete"];
?>
            <li 
              class="list-group-item d-flex justify-content-between align-items-center"
            >
              <!-- Task -->
              <?= $complete ? '<del class="text-secondary">' : ''; ?>
                <?= $task; ?>
              <?= $complete ? '</del>' : ''; ?>

              <!-- Complete/delete buttons -->
              <form
                class="btn-group"
                role="group"
                aria-label="Task actions"
                method="POST"
              >
                <button
                  type="submit"
                  name="<?= $complete ? 'un' : ''; ?>complete"
                  class="btn btn-success<?= $complete ? ' active' : ''; ?>"
                  <?= $complete ? ' aria-pressed="true"' : ''; ?>
                  aria-label="Complete"
                >
                  <i class="fas fa-check fa-fw"></i>
                </button>
                <input type="hidden" name="id" value="<?= $id; ?>" />
                <button
                  type="submit"
                  name="delete-one"
                  class="btn btn-danger"
                  aria-label="Delete"
                >
                  <i class="fas fa-trash-alt fa-fw"></i>
                </button>
              </form>
            </li>
<?php
}
?>
          </ul>

          <!-- Delete all tasks button -->
          <form method="POST">
            <button
              type="submit"
              name="delete-all"
              class="btn btn-danger px-5 d-block mx-auto"<?= $count === 0 ? ' disabled' : ''; ?>
            >
              DELETE ALL TASKS
            </button>
          </form>
        </div>
      </div>

      <!-- Explanation -->
      <p class="lead font-italic">
        View the source on
        <a href="https://github.com/tywmick/php-todo">Github</a>.
      </p>

      <p>
        Just wrote my first line of PHP two days ago, and here's my first
        project! Simple to-do list app with a
        <a href="https://www.sqlite.org/index.html">SQLite</a> database (this is
        coincidentally also my first SQL project). You can add new tasks, mark
        tasks complete, "uncomplete" tasks, and delete them, too. The task list
        is arranged so that incomplete tasks show up first; incomplete tasks are
        then arranged in descending order of creation date, and completed tasks
        are arranged in descending order of <em>completion</em> date.
      </p>
      <p>
        This project also uses
        <a href="https://en.wikipedia.org/wiki/Prepared_statement"
          >prepared statements</a
        >
        to guard against SQL injection.
      </p>

      <p>
        Hope you enjoy this demo, and
        <a href="https://tymick.me/connect">let me know</a> if you have any
        questions!
      </p>

      <p>Sincerely,</p>
      <div
        class="display-1 mb-4"
        style="font-family: 'Dawning of a New Day', cursive;"
      >
        <a class="text-reset text-decoration-none" href="http://tymick.me">
          Ty
        </a>
      </div>
    </div>

    <!-- Creative Commons license -->
    <footer class="text-center mt-auto mb-3">
      <a
        rel="license"
        href="http://creativecommons.org/licenses/by/4.0/"
        title="Creative Commons Attribution 4.0 International License"
        class="text-reset text-decoration-none"
      >
        <i class="fab fa-creative-commons"></i>&#x0200A;<i
          class="fab fa-creative-commons-by"
        ></i>
      </a>
      2020
      <a
        href="http://tymick.me"
        xmlns:cc="http://creativecommons.org/ns#"
        property="cc:attributionName"
        rel="cc:attributionURL"
        class="text-reset text-decoration-none"
      >
        Tyler&nbsp;Westin&nbsp;Mick
      </a>
    </footer>
  </body>
</html>
