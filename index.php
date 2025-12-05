<?php
session_start();

// Username & password yang benar
$valid_user = "nabila";
$valid_pass = "1234";

$error = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == $valid_user && $password == $valid_pass) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = "Dosen";
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>POLGAN MART - Login</title>
    <style>
        body { font-family: Arial; background:#f1f3f7; display:flex; justify-content:center; align-items:center; height:100vh; }
        .box { background:white; padding:25px; width:350px; border-radius:10px; text-align:center; }
        input { width:100%; padding:10px; margin:7px 0; border-radius:6px; border:1px solid #ccc; }
        button { width:100%; padding:10px; background:#0b63f6; border:none; border-radius:6px; color:white; font-size:16px; margin-top:10px; cursor:pointer; }
        .cancel { background:#ccc; color:black; margin-top:5px; }
        .error { background:#ffdddd; padding:8px; color:#b30000; border-radius:6px; margin-bottom:10px; }
    </style>
</head>
<body>

<div class="box">
    <h2>POLGAN MART</h2>

    <?php if ($error != "") { echo "<div class='error'>$error</div>"; } ?>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button name="login">Login</button>
        <button type="reset" class="cancel">Batal</button>
    </form>
</div>

</body>
</html>