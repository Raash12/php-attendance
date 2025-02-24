<?php
session_start();
require_once('../classes/actions.class.php'); // Adjusted path
$actionClass = new Actions();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $user = $actionClass->getUserByEmail($email);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: home.php'); // Redirect to your main page    
        exit(); // Ensure exit after redirect
    } else {
        $_SESSION['flashdata'] = ['type' => 'danger', 'msg' => 'Invalid email or password.'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Light background color */
        }
        .container {
            max-width: 400px; /* Set a max width for the container */
            margin-top: 100px; /* Center it vertically */
            padding: 20px; /* Add some padding */
            background-color: white; /* White background for the form */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }
        h2 {
            margin-bottom: 20px; /* Space below the heading */
            text-align: center; /* Center the heading */
        }
        .btn-primary {
            width: 100%; /* Full-width button */
            padding: 10px; /* Add padding for better touch target */
        }
        .alert {
            margin-bottom: 20px; /* Space below the alert */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (isset($_SESSION['flashdata'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['flashdata']['msg'] ?>
                <?php unset($_SESSION['flashdata']); ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST">
            <input type="email" name="email" required placeholder="Email" class="form-control mb-2">
            <input type="password" name="password" required placeholder="Password" class="form-control mb-2">
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>
</html>