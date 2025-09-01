<?php
session_start();

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$errorLogin = "";

if (isset($_POST['login'])) {
    if ($username === 'admin' && $password === 'test') {
        $_SESSION['logged_in'] = true;
        header('Location: admin.php');
        exit();
    } else {
        $errorLogin = "Wrong credentials";
    } 
    
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cgrd Testtask Backend</title>
    <link rel="stylesheet" href="./css/styles.css">

</head>

<body>
    <div class="login-container">
        <img src="../img/logo.svg" alt="logo">
        <?php if (!empty($errorLogin)): ?>
            <span class="error"><?= $errorLogin ?></span>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="username" placeholder="Username">
            <input type="text" name="password" placeholder="Password">
            <button type="submit" name="login">Login</button>
        </form>
    </div>

</body>

</html>