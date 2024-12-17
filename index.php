<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Connect to the database
include 'db.php';

// Fetch the logged-in user's information
$user_id = $_SESSION['user_id'];
$query = "SELECT username FROM users WHERE id = $user_id";
$result = $conn->query($query);

if ($result && $result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $username = $user['username'];
} else {
    // If user data is invalid, log them out
    session_destroy();
    header("Location: login.php");
    exit();
}

// Check for file inclusion
$file = isset($_GET['file']) ? $_GET['file'] : 'default.txt';
$file_path = __DIR__ . "/files/" . $file;

// Restrict access to `flag.txt` if the user is not 'admin'
if ($file === 'flag.txt' && $username !== 'admin') {
    $content = "Unauthorized access. Only the admin can view this file.";
} else {
    if (file_exists($file_path) && is_readable($file_path)) {
        $content = file_get_contents($file_path);
    } else {
        $content = "File not found or not accessible.";
    }
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
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <p>Select a file to display its content:</p>
        <ul>
            <li><a href="index.php?file=notes.txt">Notes File</a></li>
            <li><a href="index.php?file=flag.txt">Flag File</a></li>
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
