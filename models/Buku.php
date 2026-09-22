<?php
class Buku {
    private $conn;

    public function __construct($db) { 
        $this->conn = $db; 
    }

    public function getAll() {
        $query = "SELECT b.*, k.nama_kategori 
                  FROM buku b 
                  JOIN kategori k ON b.id_kategori = k.id_kategori 
                  ORDER BY b.id_buku DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getKategori() {
        $query = "SELECT * FROM kategori";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($judul, $pengarang, $id_kategori, $tahun, $cover) {
        $query = "INSERT INTO buku (judul_buku, pengarang, id_kategori, tahun, cover, status) 
                  VALUES (:judul, :pengarang, :kategori, :tahun, :cover, 'Tersedia')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':judul', $judul);
        $stmt->bindParam(':pengarang', $pengarang);
        $stmt->bindParam(':kategori', $id_kategori);
        $stmt->bindParam(':tahun', $tahun);
        $stmt->bindParam(':cover', $cover);
        return $stmt->execute();
    }

    // FUNGSI HAPUS BUKU
    public function delete($id_buku) {
        $query = "DELETE FROM buku WHERE id_buku = :id_buku";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_buku', $id_buku);
        return $stmt->execute();
    }

    // FUNGSI EDIT / UPDATE BUKU
    public function update($id_buku, $judul, $pengarang, $id_kategori, $tahun, $cover = null) {
        // Jika cover baru diunggah, update beserta covernya
        if ($cover) {
            $query = "UPDATE buku SET judul_buku = :judul, pengarang = :pengarang, id_kategori = :kategori, tahun = :tahun, cover = :cover WHERE id_buku = :id_buku";
        } else {
            // Jika cover tidak diganti, biarkan cover yang lama
            $query = "UPDATE buku SET judul_buku = :judul, pengarang = :pengarang, id_kategori = :kategori, tahun = :tahun WHERE id_buku = :id_buku";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':judul', $judul);
        $stmt->bindParam(':pengarang', $pengarang);
        $stmt->bindParam(':kategori', $id_kategori);
        $stmt->bindParam(':tahun', $tahun);
        $stmt->bindParam(':id_buku', $id_buku);

        if ($cover) {
            $stmt->bindParam(':cover', $cover);
        }

        return $stmt->execute();
    }

    // FUNGSI PROSES PEMINJAMAN
    public function prosesPinjam($id_buku, $id_user) {
        try {
            $this->conn->beginTransaction();

            $tgl_pinjam = date('Y-m-d');
            $tgl_jatuh_tempo = date('Y-m-d', strtotime('+7 days')); // Pengembalian 7 hari

            // 1. Catat ke tabel peminjaman
            $queryPinjam = "INSERT INTO peminjaman (id_user, id_buku, tgl_pinjam, tgl_jatuh_tempo, status) 
                            VALUES (:id_user, :id_buku, :tgl_pinjam, :tgl_jatuh_tempo, 'Dipinjam')";
            $stmt1 = $this->conn->prepare($queryPinjam);
            $stmt1->bindParam(':id_user', $id_user);
            $stmt1->bindParam(':id_buku', $id_buku);
            $stmt1->bindParam(':tgl_pinjam', $tgl_pinjam);
            $stmt1->bindParam(':tgl_jatuh_tempo', $tgl_jatuh_tempo);
            $stmt1->execute();

            // 2. Ubah status buku menjadi Dipinjam
            $queryBuku = "UPDATE buku SET status = 'Dipinjam' WHERE id_buku = :id_buku";
            $stmt2 = $this->conn->prepare($queryBuku);
            $stmt2->bindParam(':id_buku', $id_buku);
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