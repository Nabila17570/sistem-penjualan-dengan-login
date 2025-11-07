<?php
session_start();
include 'users.php';

$error = "";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $found = false;

    foreach ($users as $user) {
        if ($user['username'] == $username && $user['password'] == $password) {
            $found = true;

            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: dashboard.php");
            exit();
        }
    }

    if (!$found) {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body { font-family: Arial; background:#f7f7f7; }
        .box { width:300px; margin:70px auto; padding:20px; background:white; border-radius:8px; }
        input { width:100%; padding:10px; margin:5px 0; }
        .btn { background:#1a73e8; padding:10px; color:white; border:none; width:100%; cursor:pointer; }
        .error { background:#ffdddd; color:#c00; padding:8px; margin-bottom:10px; }
    </style>
</head>
<body>

<div class="box">
    <h3 align="center">POLGAN MART</h3>

    <?php if ($error != "") { ?>
        <div class="error"><?= $error ?></div>
    <?php } ?>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button class="btn" name="login">Login</button>
    </form>
</div>

</body>
</html>