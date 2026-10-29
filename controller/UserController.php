<?php
require_once 'models/User.php';

class UserController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function index() {
        // Cek apakah yang login Admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Admin') {
            header("Location: index.php?page=buku");
            exit;
        }

        $userModel = new User($this->db);
        $userList = $userModel->getAllUser();

        include 'views/user/index.php';
    }

    // ==========================================
    // TAMBAHKAN FUNGSI HAPUS DI SINI
    // ==========================================
    public function hapus() {
        // Cek apakah yang menghapus adalah Admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Admin') {
            header("Location: index.php?page=buku");
            exit;
        }

        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $userModel = new User($this->db);
            $userModel->deleteUser($id);
        }

        // Kembali ke halaman kelola anggota
        header("Location: index.php?page=user");
        exit;
    }
}
?>