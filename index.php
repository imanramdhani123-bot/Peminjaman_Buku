<?php
session_start(); // HARUS ADA DI BARIS PERTAMA

require_once 'database/db.php';
require_once 'controller/AuthController.php';
require_once 'controller/BukuController.php';
require_once 'controller/PeminjamanController.php';

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
    default:
        (new BukuController())->index();
        break;
}
?>