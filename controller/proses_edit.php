<?php
session_start();
require_once '../database/db.php';

// Otomatis mencari variabel koneksi database dari db.php
$koneksi_db = null;

if (class_exists('Database')) {
    $database = new Database();
    if (method_exists($database, 'getConnection')) {
        $koneksi_db = $database->getConnection();
    } elseif (property_exists($database, 'conn')) {
        $koneksi_db = $database->conn;
    } elseif (property_exists($database, 'koneksi')) {
        $koneksi_db = $database->koneksi;
    }
}

if (!$koneksi_db) {
    $koneksi_db = $koneksi ?? $conn ?? $db ?? $mysqli ?? null;
}

if (!$koneksi_db) {
    die("Gagal menghubungkan ke database.");
}

// Proses jika form edit dikirim (Method POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_buku     = $_POST['id_buku'];
    $judul_buku  = $_POST['judul_buku'];
    $pengarang   = $_POST['pengarang'];
    $id_kategori = $_POST['id_kategori'];
    $tahun       = $_POST['tahun'];

    // Cek apakah ada upload cover baru
    if (isset($_FILES['cover']['name']) && $_FILES['cover']['name'] != '') {
        $cover_name = $_FILES['cover']['name'];
        $tmp_name   = $_FILES['cover']['tmp_path'] ?? $_FILES['cover']['tmp_name'];
        $upload_dir = '../asset/uploads/';

        move_uploaded_file($tmp_name, $upload_dir . $cover_name);

        $query = "UPDATE buku SET 
                    judul_buku = '$judul_buku', 
                    pengarang = '$pengarang', 
                    id_kategori = '$id_kategori', 
                    tahun = '$tahun', 
                    cover = '$cover_name' 
                  WHERE id_buku = '$id_buku'";
    } else {
        // Jika cover tidak diubah
        $query = "UPDATE buku SET 
                    judul_buku = '$judul_buku', 
                    pengarang = '$pengarang', 
                    id_kategori = '$id_kategori', 
                    tahun = '$tahun' 
                  WHERE id_buku = '$id_buku'";
    }

    if ($koneksi_db instanceof mysqli) {
        $result = mysqli_query($koneksi_db, $query);
    } elseif ($koneksi_db instanceof PDO) {
        $result = $koneksi_db->query($query);
    } else {
        $result = mysqli_query($koneksi_db, $query);
    }

    if ($result) {
        header("Location: ../index.php?page=buku&pesan=edit_sukses");
        exit();
    } else {
        echo "Gagal memperbarui data buku.";
    }
} else {
    header("Location: ../index.php?page=buku");
    exit();
}
?>