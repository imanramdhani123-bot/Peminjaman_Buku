<?php
class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($email, $password) {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Cek password hash atau plain text
            if (password_verify($password, $user['password']) || $password === $user['password']) {
                return $user;
            }
        }
        return false;
    }

    public function register($nama_lengkap, $email, $password) {
        // Encrypt password demi keamanan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $query = "INSERT INTO users (nama_lengkap, email, password) VALUES (:nama_lengkap, :email, :password)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':nama_lengkap', $nama_lengkap);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        
        return $stmt->execute();
    }

    // Mengambil seluruh data user untuk Kelola Anggota
    public function getAllUser() {
        $query = "SELECT * FROM users"; 
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==========================================
    // FUNGSI HAPUS YANG SUDAH DIPERBAIKI
    // ==========================================
    public function deleteUser($id) {
        $query = "DELETE FROM users WHERE id_user = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>