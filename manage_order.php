<?php
session_start();

if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'admin') {
    // Pastikan hanya admin yang dapat mengakses halaman ini

    try {
        // Koneksi ke database
        include("./components/database.php");

        // Query untuk mengambil daftar pesanan
        $query = "SELECT o.id_order, u.email AS user_email, o.tanggal_order, o.status_order, o.cara_pembayaran
                    FROM `Order` o
                    INNER JOIN users u ON o.id_user = u.id_user
                    ORDER BY o.tanggal_order DESC";

        $stmt = $conn->query($query);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Jika pengguna belum login atau bukan admin, arahkan mereka ke halaman lain
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./components/heading.config.php"); ?>
    <title>Makan Yuk - Manage Order</title>
    <style>
        .table {
            width: 100%;
        }
    </style>
</head>

<body>
    <?php include("./components/navbar/navbar.php"); ?>

    <div class="container mt-5">
        <h2>Manage Orders</h2>
        <?php
        if (count($orders) > 0) {
            echo '<table class="table table-bordered mt-4">';
            echo '<thead>';
            echo '<tr>';
            echo '<th scope="col">Order ID</th>';
            echo '<th scope="col">User Email</th>';
            echo '<th scope="col">Order Date</th>';
            echo '<th scope="col">Status</th>';
            echo '<th scope="col">Payment Method</th>';
            echo '<th scope="col">Actions</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            foreach ($orders as $order) {
                echo '<tr>';
                echo '<td>' . $order['id_order'] . '</td>';
                echo '<td>' . $order['user_email'] . '</td>';
                echo '<td>' . $order['tanggal_order'] . '</td>';
                echo '<td>' . $order['status_order'] . '</td>';
                echo '<td>' . $order['cara_pembayaran'] . '</td>';
                echo '<td>';
                echo '<a href="view_order.php?id=' . $order['id_order'] . '" class="btn btn-info">View Order</a>';
                echo '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>No orders available.</p>';
        }
        ?>
    </div>

    <?php include("./components/footer/footer.php"); ?>
</body>

</html>