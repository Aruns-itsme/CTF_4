<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Vulnerable: File inclusion based on user input
$file = isset($_GET['file']) ? $_GET['file'] : 'flag.txt';
$file_path = __DIR__ . "/files/" . $file;

if (file_exists($file_path) && is_readable($file_path)) {
    $content = file_get_contents($file_path);
} else {
    $content = "File not found or not accessible.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
        }
        .container h1 {
            margin-bottom: 20px;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
            text-align: left;
            background-color: #f9f9f9;
        }
        .logout {
            margin-top: 20px;
            color: red;
        }
        .logout a {
            text-decoration: none;
            color: red;
            font-weight: bold;
        }
        .logout a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Your To-Do List</h1>
        <p>Select a file to display its content:</p>
        <ul>
            <li><a href="index.php?file=default.txt">Default File</a></li>
            <li><a href="index.php?file=notes.txt">Notes File</a></li>
        </ul>

        <div class="file-content">
            <h2>File Content</h2>
            <p><?php echo nl2br(htmlspecialchars($content)); ?></p>
        </div>

        <p class="logout">
            <a href="logout.php">Logout</a>
        </p>
    </div>
</body>
</html>

