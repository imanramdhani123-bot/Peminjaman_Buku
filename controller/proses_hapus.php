<?php
session_start();
require_once '../database/db.php';

// Inisialisasi variabel koneksi
$koneksi_db = null;

// Cek apakah db.php menggunakan Class Database
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

// Cek jika variabel standar PHP
if (!$koneksi_db) {
    $koneksi_db = $koneksi ?? $conn ?? $db ?? $mysqli ?? null;
}

if (!$koneksi_db) {
    die("Gagal menghubungkan ke database. Silakan periksa isi file database/db.php kamu.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    $query = "DELETE FROM buku WHERE id_buku = '$id'";
    
    // Eksekusi untuk mysqli atau PDO
    if ($koneksi_db instanceof mysqli) {
        $result = mysqli_query($koneksi_db, $query);
    } elseif ($koneksi_db instanceof PDO) {
        $result = $koneksi_db->query($query);
    } else {
        $result = mysqli_query($koneksi_db, $query);
    }

    if ($result) {
        header("Location: ../index.php?page=buku&pesan=hapus_sukses");
        exit();
    } else {
        echo "Gagal menghapus data buku.";
    }
} else {
    header("Location: ../index.php?page=buku");
    exit();
}
?>