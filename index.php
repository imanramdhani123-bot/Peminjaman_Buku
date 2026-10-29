<?php
session_start(); // HARUS ADA DI BARIS PERTAMA

require_once 'database/db.php';
require_once 'controller/AuthController.php';
require_once 'controller/BukuController.php';
require_once 'controller/PeminjamanController.php';
require_once 'controller/UserController.php'; // Load controller user

$page = $_GET['page'] ?? 'buku';

switch ($page) {
    case 'login':
        (new AuthController())->login();
        break;
    case 'register':
        (new AuthController())->register();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;
    case 'buku':
        (new BukuController())->index();
        break;
    case 'peminjaman':
        (new PeminjamanController())->index();
        break;
    case 'detail-peminjaman':
        (new PeminjamanController())->detail();
        break;
    case 'user':
        (new UserController())->index();
        break;
    case 'hapus-user': // <--- Rute baru untuk memproses hapus anggota
        (new UserController())->hapus();
        break;
    default:
        (new BukuController())->index();
        break;
}
?>