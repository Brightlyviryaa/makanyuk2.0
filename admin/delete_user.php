<?php
session_start();

if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin' && isset($_GET['id'])) {
    // Pastikan user sudah login, memiliki peran admin, dan ID pengguna yang akan dihapus diberikan
    $user_id = $_GET['id'];

    try {
        // Koneksi ke database
        include("../components/database.php");

        // Query untuk menghapus pengguna berdasarkan ID
        $deleteQuery = "DELETE FROM users WHERE id_user = :id_user";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bindParam(':id_user', $user_id);
        $stmt->execute();

        // Redirect to user management page after the delete
        header('Location: ../user.php');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Jika pengguna belum login, bukan admin, atau ID pengguna tidak valid, arahkan mereka ke halaman lain
    header('Location: ../login.php');
    exit();
}
?>