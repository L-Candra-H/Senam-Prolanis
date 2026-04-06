<?php
@session_start();

// Timeout otomatis: 15 menit (900 detik)
$timeout = 900;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit;
}
$_SESSION['last_activity'] = time();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// File: public/index.php

// Autoload file (bisa gunakan spl_autoload_register atau require langsung)
require_once __DIR__ . '/../application/controllers/ParticipantController.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'index';
$controller = new ParticipantController();

switch($action) {
    case 'form':
        // Tampilkan form input data
        require_once __DIR__ . '/../application/views/participants/form.php';
        break;
    case 'store':
        $controller->store();
        break;
    case 'delete':
        $controller->delete();
        break;
    case 'riwayat':
        $controller->riwayat();
        break;
    case 'index':
    default:
        $controller->index();
        break;
    case 'cetak':
        $controller->cetak();
        break;
    case 'edit':
        $controller->edit(); // Menampilkan form edit
        break;
    case 'update':
        $controller->update(); // Proses simpan hasil edit
        break;

}
?>

