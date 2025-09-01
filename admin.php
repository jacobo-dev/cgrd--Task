<?php

session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit();
}

require_once './config/db.php';
require_once './models/taskModel.php';

$db = new Database();
$conn = $db->connect();
$newTaskModel = new TaskModel($conn);
$allTaks = $newTaskModel->getAllTasks();

if (isset($_POST['create'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $result = $newTaskModel->newTask($title, $description);
    $_SESSION['notification'] = "Task added successfully!";
    $allTaks = $newTaskModel->getAllTasks();
    header('Location: admin.php');
    exit();
}

if (isset($_POST['delete'])) {
    $deleteId = $_POST['delete_id'];
    $newTaskModel->removeTask($deleteId);
    $_SESSION['notification'] = "Task was deleted!";
    header('Location: admin.php');
    exit();
}
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    $newTaskModel->updateTask($title, $description, $id);
    $_SESSION['notification'] = "Task was updated!";
    header('Location: admin.php');
    exit();
}

if (isset($_POST['log-out'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: index.php');
    exit();
}

$notification = "";
if (isset($_SESSION['notification'])) {
    $notification = $_SESSION['notification'];
    unset($_SESSION['notification']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin</title>
    <link rel="stylesheet" href="css/admin.css">
</head>

<body>

    <img src="./img/logo.svg" alt="logo">

    <?php if (!empty($notification)): ?>
        <span class="notification"><?= $notification ?></span>
    <?php endif; ?>

    <div>

        <?php if ($allTaks): ?>
            <div>
                <h2>All Tasks</h2>
            </div>
            <ul>
                <?php foreach ($allTaks as $task): ?>
                    <li data-id="<?= $task['id'] ?>" data-title="<?= $task['title'] ?>" data-description="<?= $task['description'] ?>">
                        <span class="task-text">
                            <span class="task-title"><?= htmlspecialchars($task['title']) ?></span>
                            <span class="task-description"><?= htmlspecialchars($task['description']) ?></span>
                        </span>
                        <div class="button">
                            <button class="edit-button">
                                <img src="./img/pencil.svg" alt="Edit">
                            </button>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="delete_id" value="<?= $task['id'] ?>">
                                <button type="submit" name="delete">
                                    <img src="./img/close.svg" alt="Delete">
                                </button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <div>
            <div class="title">
                <h2 id="title-form">Create News Task</h2>
                <button id="reset-btn" class="hidden">
                    <img src="./img/close.svg" alt="reset">
                </button>
            </div>
            <form method="post">
                <input type="hidden" name="id" id="task-id" value="" required>
                <input type="text" name="title" id="task-title" placeholder="Title" required>
                <textarea type="text" name="description" id="task-description" placeholder="Description" required></textarea>

                <button type="submit" name="create" id="button-c">Add Task</button>
                
            </form>

            <form method="post">
                <button type="submit" name="log-out" id="button-c">Log Out</button>
            </form>

        </div>

    </div>

    <script>
        const buttonsEdit = document.querySelectorAll('.edit-button')
        const idTask = document.querySelector('#task-id')
        const titleTask = document.querySelector('#task-title')
        const descriptionTask = document.querySelector('#task-description')
        const titleFomr = document.querySelector('#title-form')
        const buttonForm = document.querySelector('#button-c')
        const resetBtn = document.querySelector('#reset-btn')

        buttonsEdit.forEach(button => {
            button.addEventListener('click', () => {
                const li = button.closest('li')
                const id = li.dataset.id
                const title = li.dataset.title
                const description = li.dataset.description

                idTask.value = id
                titleTask.value = title
                descriptionTask.value = description

                titleFomr.textContent = 'Edit'
                buttonForm.name = "update"
                buttonForm.textContent = "Save"

                resetBtn.classList.remove('hidden')
            })
        })

        resetBtn.addEventListener('click', () => {
            idTask.value = ''
            titleTask.value = ''
            descriptionTask.value = ''
            titleFomr.textContent = 'Create New Task'
            buttonForm.name = 'create'
            buttonForm.textContent = 'Add Task'
            resetBtn.classList.add('hidden')
        })
    </script>


</body>

</html>