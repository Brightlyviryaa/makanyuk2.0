<?php
session_start();

if (isset($_SESSION['user_id'])) {
    // Pastikan user sudah login
    $user_id = $_SESSION['user_id'];

    try {
        // Koneksi ke database
        include("./components/database.php");

        // Membuat pesanan baru dengan cara pembayaran "cash on delivery"
        $orderQuery = "INSERT INTO `Order` (id_user, tanggal_order, status_order, cara_pembayaran) VALUES (:user_id, NOW(), 'pending', 'Cash on Delivery')";
        $orderStmt = $conn->prepare($orderQuery);
        $orderStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $orderStmt->execute();

        // Mendapatkan ID pesanan yang baru saja dibuat
        $lastOrderId = $conn->lastInsertId();

        // Mengambil item dari keranjang belanja
        $cartQuery = "SELECT * FROM Shopping_Cart WHERE id_user = :user_id";
        $cartStmt = $conn->prepare($cartQuery);
        $cartStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $cartStmt->execute();
        $cartItems = $cartStmt->fetchAll(PDO::FETCH_ASSOC);

        // Memindahkan item dari keranjang belanja ke Order_Item
        foreach ($cartItems as $cartItem) {
            $menuId = $cartItem['id_menu'];
            $jumlah = $cartItem['jumlah'];

            $orderItemQuery = "INSERT INTO Order_Item (id_order, id_menu, jumlah) VALUES (:order_id, :menu_id, :quantity)";
            $orderItemStmt = $conn->prepare($orderItemQuery);
            $orderItemStmt->bindParam(':order_id', $lastOrderId, PDO::PARAM_INT);
            $orderItemStmt->bindParam(':menu_id', $menuId, PDO::PARAM_INT);
            $orderItemStmt->bindParam(':quantity', $jumlah, PDO::PARAM_INT);
            $orderItemStmt->execute();
        }

        // Hapus item dari keranjang belanja
        $deleteCartQuery = "DELETE FROM Shopping_Cart WHERE id_user = :user_id";
        $deleteCartStmt = $conn->prepare($deleteCartQuery);
        $deleteCartStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $deleteCartStmt->execute();

        // Redirect pengguna ke halaman lain (misalnya, halaman konfirmasi pesanan)
        header("Location: shopping_cart.php");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Jika pengguna belum login, arahkan mereka ke halaman login
    header('Location: login.php');
    exit();
}
?>