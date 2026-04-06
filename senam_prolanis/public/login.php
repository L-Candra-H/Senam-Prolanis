<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// Cegah login ulang jika sudah login
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$err = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === "senam" && $password === "prolanis") {
        $_SESSION['login'] = true;
        header("Location: index.php");
        exit;
    } else {
        $err = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Senam Prolanis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/senam_prolanis/public/css/style.css" rel="stylesheet"><!-- ini penambahan untuk custom style -->
</head>
<body>
    <div class="login-wrapper">
        <div class="card shadow">
            <div class="card-body">
                <h4 class="card-title text-center mb-4">Senam Prolanis</h4>

                <?php if (isset($_GET['timeout'])): ?>
                    <div class="alert alert-warning">Sesi Anda telah berakhir karena tidak aktif terlalu lama.</div>
                <?php endif; ?>

                <?php if ($err): ?>
                    <div class="alert alert-danger"><?= $err ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Masuk</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
