<?php
// Commit 3: Proses Login dan Session
session_start();

// Cek apakah user sudah login, jika ya, arahkan ke dashboard
if (isset($_SESSION['status']) && $_SESSION['status'] == 'login') {
    header("location: dashboard.php");
    exit;
}

// Inisialisasi pesan error
$pesan_error = '';

// Proses form submission
if (isset($_POST['login'])) {
    // Commit 3: Periksa kecocokan username dan password.
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Commit 2: Gunakan data login statis
    $valid_username = 'nabila';
    $valid_password = '1234'; // Password statis

    if ($username === $valid_username && $password === $valid_password) {
        // Commit 3: Jika benar, buat session dan arahkan ke halaman dashboard.php.
        $_SESSION['username'] = $username;
        $_SESSION['status'] = 'login';
        header("location: dashboard.php");
        exit;
        // Mengambil role dari sesi. Role ini diasumsikan sudah disimpan 
// di index.php sebagai $_SESSION['role'] = "Dosen";
$role = isset($_SESSION['role']) ? htmlspecialchars($_SESSION['role']) : "Pengguna"; 
    } else {
        // Commit 3: Jika salah, tampilkan pesan error di halaman login.
        $pesan_error = 'Username atau password salah!'; // Sesuai dengan Tampilan index.php ketika username/password salah
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POLGAN MART - Login</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); width: 300px; text-align: center; }
        h2 { color: #007bff; margin-bottom: 20px; }
        .error { background-color: #ffe8e8; color: #cc0000; padding: 10px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #cc0000; }
        .form-group { margin-bottom: 15px; text-align: left; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-login { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn-batal { display: block; margin-top: 10px; color: #007bff; text-decoration: none; }
        .footer { margin-top: 20px; font-size: 0.8em; color: #aaa; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>POLGAN MART</h2>
        
        <?php if ($pesan_error): ?>
            <div class="error"><?php echo $pesan_error; ?></div>
        <?php endif; ?>

        <form action="index.php" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="login" class="btn-login">Login</button>
            <a href="#" class="btn-batal">Batal</a>
        </form>
        <div class="footer">
            &copy; 2025 POLGAN MART
        </div>
    </div>
</body>
</html>