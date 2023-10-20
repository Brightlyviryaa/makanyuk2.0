<?php
session_start();

if (isset($_SESSION['user_id'])) {
    // Pastikan user sudah login sebelum menambahkan ke keranjang
    if (isset($_GET['menu_id'])) {
        $menu_id = $_GET['menu_id'];
        $user_id = $_SESSION['user_id'];
        $jumlah = 1;

        try {
            // Koneksi ke database
            include("./components/database.php");

            // Cek apakah item sudah ada dalam keranjang pengguna
            $query = "SELECT id_shopping_cart FROM Shopping_Cart WHERE id_user = :user_id AND id_menu = :menu_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':menu_id', $menu_id);
            $stmt->execute();
            $row = $stmt->fetch();

            if ($row) {
                // Jika item sudah ada dalam keranjang, update jumlah
                $query = "UPDATE Shopping_Cart SET jumlah = jumlah + :jumlah WHERE id_user = :user_id AND id_menu = :menu_id";
            } else {
                // Jika item belum ada dalam keranjang, tambahkan item baru
                $query = "INSERT INTO Shopping_Cart (id_user, id_menu, jumlah) VALUES (:user_id, :menu_id, :jumlah)";
            }

            $stmt = $conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':menu_id', $menu_id);
            $stmt->bindParam(':jumlah', $jumlah);
            $stmt->execute();

            // Redirect ke halaman keranjang belanja atau halaman lain sesuai kebutuhan
            header('Location: index.php');
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
} else {
    // Jika pengguna belum login, arahkan mereka ke halaman login
    header('Location: login.php');
    exit();
}
?>