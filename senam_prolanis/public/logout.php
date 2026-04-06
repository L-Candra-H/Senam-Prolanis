<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
    <meta http-equiv="refresh" content="2;url=login.php">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .fade-out { animation: fadeOut 2s ease-in-out forwards; }
        @keyframes fadeOut {
            0% { opacity: 1; transform: scale(1); }
            100% { opacity: 0; transform: scale(0.95); }
        }
    </style>
</head>
<body>
    <div class="alert alert-success text-center fade-out">
        <h4 class="mb-0">Berhasil logout</h4>
        <small>Mengalihkan ke halaman login...</small>
    </div>
</body>
</html>
