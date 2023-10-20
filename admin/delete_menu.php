<?php
session_start();

if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'admin') {
    // Pastikan hanya admin yang dapat mengakses halaman ini

    if (isset($_GET['id'])) {
        $id_menu = $_GET['id'];

        try {
            // Koneksi ke database
            include("../components/database.php");

            // Hapus terlebih dahulu semua data yang terkait dalam tabel Shopping_Cart
            $deleteShoppingCartQuery = "DELETE FROM Shopping_Cart WHERE id_menu = :id_menu";
            $deleteShoppingCartStmt = $conn->prepare($deleteShoppingCartQuery);
            $deleteShoppingCartStmt->bindParam(':id_menu', $id_menu);
            $deleteShoppingCartStmt->execute();

            // Hapus data yang terkait dalam tabel Order_Item
            $deleteOrderItemQuery = "DELETE FROM Order_Item WHERE id_menu = :id_menu";
            $deleteOrderItemStmt = $conn->prepare($deleteOrderItemQuery);
            $deleteOrderItemStmt->bindParam(':id_menu', $id_menu);
            $deleteOrderItemStmt->execute();

            // Setelah semua data yang terkait dihapus, barulah hapus menu dari tabel Menu
            $deleteMenuQuery = "DELETE FROM Menu WHERE id_menu = :id_menu";
            $deleteMenuStmt = $conn->prepare($deleteMenuQuery);
            $deleteMenuStmt->bindParam(':id_menu', $id_menu);
            $deleteMenuStmt->execute();

            // Redirect to menu management page
            header('Location: ../manage_menu.php');
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
} else {
    // Jika pengguna belum login atau bukan admin, arahkan mereka ke halaman lain
    header('Location: ../login.php');
    exit();
}
?>