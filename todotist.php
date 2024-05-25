<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <style>
        /* Global Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
        }

        /* Container Styles */
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Form Styles */
        form {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        input[type="text"] {
            flex: 1;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
        }

        button[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #3e8e41;
        }

        /* List Styles */
        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        li {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        li:last-child {
            border-bottom: none;
        }

        li:hover {
            background-color: #f0f0f0;
        }

        /* Task Styles */
        .task {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .task-text {
            flex: 1;
        }

        .task-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .task-actions form {
            margin: 0;
        }

        .task-actions button {
            padding: 5px 10px;
            font-size: 14px;
            background-color: #4CAF50;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
            color: white;
        }

        .task-actions button:hover {
            background-color: red;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 300px;
            text-align: center;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-buttons button {
            padding: 10px 20px;
            margin: 5px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }

        .delete-confirm {
            background-color: red;
            color: white;
        }

        .delete-cancel {
            background-color: grey;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Simple To-Do List</h2>
        <form method="post" action="">
            <input type="text" name="task" placeholder="Enter a new task">
            <button type="submit">Add Task</button>
        </form>

        <?php
        // Function to read tasks from the file
        function getTasks() {
            if (file_exists('todo.txt')) {
                return file('todo.txt', FILE_IGNORE_NEW_LINES);
            }
            return [];
        }

        // Function to save tasks to the file
        function saveTasks($tasks) {
            file_put_contents('todo.txt', implode(PHP_EOL, $tasks) . PHP_EOL);
        }

        // Check if form is submitted to add a task
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task']) && !empty($_POST['task'])) {
            $task = $_POST['task'];
            $tasks = getTasks();
            $tasks[] = $task;
            saveTasks($tasks);
        }

        // Check if form is submitted to delete a task
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_task'])) {
            $delete_task = $_POST['delete_task'];
            $tasks = getTasks();
            if (($key = array_search($delete_task, $tasks)) !== false) {
                unset($tasks[$key]);
            }
            saveTasks($tasks);
        }

        // Display existing tasks
        $tasks = getTasks();
        if (count($tasks) > 0) {
            echo "<ul>";
            foreach ($tasks as $task) {
                echo "<li class='task'>";
                echo "<span class='task-text'>$task</span>";
                echo "<span class='task-actions'>";
                echo "<button onclick=\"openModal('$task')\">Delete</button>";
                echo "</span>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No tasks yet.</p>";
        }
        ?>

        <!-- The Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>Are you sure you want to delete this task?</p>
                <div class="modal-buttons">
                    <form method="post" action="">
                        <input type="hidden" name="delete_task" id="deleteTaskInput">
                        <button type="submit" class="delete-confirm">Yes</button>
                    </form>
                    <button class="delete-cancel" onclick="closeModal()">No</button>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Get the modal
        var modal = document.getElementById("myModal");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // Function to open the modal
        function openModal(task) {
            modal.style.display = "block";
            document.getElementById("deleteTaskInput").value = task;
        }

        // Function to close the modal
        function closeModal() {
            modal.style.display = "none";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            closeModal();
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
