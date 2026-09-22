<?php
class Peminjaman {
    private $conn;

    public function __construct($db) { 
        $this->conn = $db; 
    }

    public function getAllByRole($role, $userId) {
        if ($role === 'Admin' || $role === 'Petugas') {
            $query = "SELECT p.*, u.nama_lengkap, b.judul_buku 
                      FROM peminjaman p 
                      JOIN users u ON p.id_user = u.id_user 
                      JOIN buku b ON p.id_buku = b.id_buku 
                      ORDER BY p.id_pinjam DESC";
            $stmt = $this->conn->prepare($query);
        } else {
            $query = "SELECT p.*, u.nama_lengkap, b.judul_buku 
                      FROM peminjaman p 
                      JOIN users u ON p.id_user = u.id_user 
                      JOIN buku b ON p.id_buku = b.id_buku 
                      WHERE p.id_user = :id_user 
                      ORDER BY p.id_pinjam DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_user', $userId);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT p.*, u.nama_lengkap, u.email, b.judul_buku, b.cover 
                  FROM peminjaman p 
                  JOIN users u ON p.id_user = u.id_user 
                  JOIN buku b ON p.id_buku = b.id_buku 
                  WHERE p.id_pinjam = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // FUNGSI PROSES PENGEMBALIAN BUKU
    public function kembalikanBuku($id_pinjam) {
        try {
            $this->conn->beginTransaction();

            // 1. Ambil data peminjaman
            $dataPinjam = $this->getById($id_pinjam);
            if (!$dataPinjam || $dataPinjam['status'] === 'Kembali') {
                return false;
            }

            $tgl_kembali = date('Y-m-d');
            $tgl_jatuh_tempo = strtotime($dataPinjam['tgl_jatuh_tempo']);
            $tgl_sekarang = strtotime($tgl_kembali);
            
            // Hitung denda jika terlambat (Rp 1.000 per hari)
            $denda = 0;
            if ($tgl_sekarang > $tgl_jatuh_tempo) {
                $selisih_hari = floor(($tgl_sekarang - $tgl_jatuh_tempo) / (60 * 60 * 24));
                $denda = $selisih_hari * 1000;
            }

            // 2. Update status transaksi peminjaman
            $queryUpdate = "UPDATE peminjaman 
                            SET tgl_kembali = :tgl_kembali, status = 'Kembali', denda = :denda 
                            WHERE id_pinjam = :id_pinjam";
            $stmt1 = $this->conn->prepare($queryUpdate);
            $stmt1->bindParam(':tgl_kembali', $tgl_kembali);
            $stmt1->bindParam(':denda', $denda);
            $stmt1->bindParam(':id_pinjam', $id_pinjam);
            $stmt1->execute();

            // 3. Ubah status buku kembali menjadi 'Tersedia'
            $queryBuku = "UPDATE buku SET status = 'Tersedia' WHERE id_buku = :id_buku";
            $stmt2 = $this->conn->prepare($queryBuku);
            $stmt2->bindParam(':id_buku', $dataPinjam['id_buku']);
            $stmt2->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>