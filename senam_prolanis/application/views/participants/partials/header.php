<?php
@session_start();
// Cek apakah sedang di halaman cetak
$isCetak = isset($_GET['action']) && $_GET['action'] === 'cetak';
?>

<header class="bg-primary text-white d-flex justify-content-between align-items-center px-4 py-3">
    <h2 class="mb-0">Senam Prolanis - Kesehatan Lebih Baik</h2>

    <?php if (!$isCetak): ?>
    <a href="logout.php" id="logoutBtn" class="btn btn-outline-light btn-sm">
        <i class="fa fa-sign-out-alt"></i> Logout
    </a>
    <?php endif; ?>
</header>

<div id="loading-screen">
    <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>


