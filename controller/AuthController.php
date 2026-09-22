<?php
require_once 'database/db.php';
require_once 'models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->userModel = new User($db);
    }

    public function login() {
        // Jika sudah login, langsung alihkan ke katalog buku
        if (isset($_SESSION['user'])) {
            header('Location: index.php?page=buku');
            exit;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->login($email, $password);

            if ($user) {
                // Simpan data login user ke dalam Session
                $_SESSION['user'] = [
                    'id_user' => $user['id_user'],
                    'nama_lengkap' => $user['nama_lengkap'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];

                // Redirect langsung ke Katalog Buku
                header('Location: index.php?page=buku');
                exit;
            } else {
                $error = "Email atau password salah!";
            }
        }

        require 'views/auth/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['password'] === $_POST['confirm_password']) {
                $this->userModel->register($_POST['nama_lengkap'], $_POST['email'], $_POST['password']);
                header('Location: index.php?page=login');
                exit;
            }
        }
        require 'views/auth/register.php';
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }
}
?>