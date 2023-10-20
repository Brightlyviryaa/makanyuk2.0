<?php
session_start();

if (isset($_SESSION['user_id'])) {
    // Pastikan user sudah login
    $user_id = $_SESSION['user_id'];

    try {
        // Koneksi ke database
        include("./components/database.php");

        // Query untuk mengambil riwayat pesanan pengguna
        $query = "SELECT o.id_order, o.tanggal_order, o.status_order
                    FROM `Order` o
                    WHERE o.id_user = :user_id
                    ORDER BY o.tanggal_order DESC";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Jika pengguna belum login, arahkan mereka ke halaman login
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./components/heading.config.php"); ?>
    <title>Makan Yuk - Order History</title>
    <style>
        .table {
            width: 100%;
        }
    </style>
</head>

<body>
    <?php include("./components/navbar/navbar.php"); ?>

    <div class="container mt-5">
        <h2>Your Order History</h2>
        <?php
        if (count($orders) > 0) {
            echo '<table class="table table-bordered mt-4">';
            echo '<thead>';
            echo '<tr>';
            echo '<th scope="col">Order ID</th>';
            echo '<th scope="col">Order Date</th>';
            echo '<th scope="col">Order Status</th>';
            echo '<th scope="col">Actions</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            foreach ($orders as $order) {
                echo '<tr>';
                echo '<td>' . $order['id_order'] . '</td>';
                echo '<td>' . $order['tanggal_order'] . '</td>';
                echo '<td>' . $order['status_order'] . '</td>';
                echo '<td><a href="order_detail.php?id=' . $order['id_order'] . '" class="btn btn-primary">View Details</a></td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>Your order history is empty.</p>';
        }
        ?>
    </div>

    <?php include("./components/footer/footer.php"); ?>
</body>

</html>