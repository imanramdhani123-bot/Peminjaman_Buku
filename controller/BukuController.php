<?php
require_once 'database/db.php';
require_once 'models/Buku.php';

class BukuController {
    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $db = (new Database())->getConnection();
        $bukuModel = new Buku($db);

        // FITUR PINJAM BUKU
        if (isset($_GET['action']) && $_GET['action'] === 'pinjam' && isset($_GET['id_buku'])) {
            $id_buku = $_GET['id_buku'];
            $id_user = $_SESSION['user']['id_user'];

            if ($bukuModel->prosesPinjam($id_buku, $id_user)) {
                header('Location: index.php?page=peminjaman');
                exit;
            }
        }

        // TAMBAH BUKU (Aman dari error folder upload)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_buku'])) {
            $judul = $_POST['judul_buku'];
            $pengarang = $_POST['pengarang'];
            $id_kategori = $_POST['id_kategori'];
            $tahun = $_POST['tahun'];

            // Gunakan default jika folder uploads belum ada
            $coverName = 'cover1.jpg'; 
            
            if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
                $coverName = time() . '_' . uniqid() . '.' . $ext;
                
                // Cek dan buat folder uploads secara otomatis jika belum ada
                $uploadDir = 'asset/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                move_uploaded_file($_FILES['cover']['tmp_name'], $uploadDir . $coverName);
            }

            $bukuModel->insert($judul, $pengarang, $id_kategori, $tahun, $coverName);
            header("Location: index.php?page=buku");
            exit;
        }

        $bukuList = $bukuModel->getAll();
        $kategoriList = $bukuModel->getKategori();
        require 'views/buku/index.php';
    }
}
?>