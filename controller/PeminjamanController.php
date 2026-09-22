<?php
require_once 'database/db.php';
require_once 'models/Peminjaman.php';

class PeminjamanController {
    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $db = (new Database())->getConnection();
        $peminjamanModel = new Peminjaman($db);

        // AKSI PENGEMBALIAN BUKU
        if (isset($_GET['action']) && $_GET['action'] === 'kembali' && isset($_GET['id'])) {
            $id_pinjam = $_GET['id'];
            $peminjamanModel->kembalikanBuku($id_pinjam);
            header('Location: index.php?page=peminjaman');
            exit;
        }
        
        $role = $_SESSION['user']['role'];
        $userId = $_SESSION['user']['id_user'];

        $peminjamanList = $peminjamanModel->getAllByRole($role, $userId);
        require 'views/peminjaman/index.php';
    }

    public function detail() {
        $id = $_GET['id'] ?? 0;
        $db = (new Database())->getConnection();
        $peminjamanModel = new Peminjaman($db);

        // AKSI PENGEMBALIAN BUKU DARI HALAMAN DETAIL
        if (isset($_GET['action']) && $_GET['action'] === 'kembali') {
            $peminjamanModel->kembalikanBuku($id);
            header('Location: index.php?page=peminjaman');
            exit;
        }

        $detail = $peminjamanModel->getById($id);
        require 'views/peminjaman/detail.php';
    }
}
?>