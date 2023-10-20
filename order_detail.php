<?php
session_start();

if (isset($_SESSION['user_id']) && isset($_GET['id'])) {
    // Pastikan user sudah login
    $user_id = $_SESSION['user_id'];
    $order_id = $_GET['id'];

    try {
        // Koneksi ke database
        include("./components/database.php");

        // Query untuk mengambil detail pesanan
        $query = "SELECT m.nama, m.harga, oi.jumlah
                    FROM Order_Item oi
                    INNER JOIN Menu m ON oi.id_menu = m.id_menu
                    WHERE oi.id_order = :order_id";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();
        $orderDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Query untuk mengambil informasi pesanan
        $orderInfoQuery = "SELECT o.id_order, o.cara_pembayaran, o.status_order
                           FROM `Order` o
                           WHERE o.id_order = :order_id";
        $orderInfoStmt = $conn->prepare($orderInfoQuery);
        $orderInfoStmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $orderInfoStmt->execute();
        $orderInfo = $orderInfoStmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Jika pengguna belum login atau ID pesanan tidak valid, arahkan mereka ke halaman lain
    header('Location: order_history.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./components/heading.config.php"); ?>
    <title>Makan Yuk - Order Detail</title>
    <style>
        .table {
            width: 100%;
        }
    </style>
</head>

<body>
    <?php include("./components/navbar/navbar.php"); ?>

    <div class="container mt-5">
        <h2>Order Detail</h2>
        <p>Order ID:
            <?php echo $order_id; ?>
        </p>
        <p>Cara Pembayaran:
            <?php echo $orderInfo['cara_pembayaran']; ?>
        </p>
        <p>Status Pesanan:
            <?php echo $orderInfo['status_order']; ?>
        </p>
        <?php
        if (count($orderDetails) > 0) {
            echo '<table class="table table-bordered mt-4">';
            echo '<thead>';
            echo '<tr>';
            echo '<th scope="col">Menu Name</th>';
            echo '<th scope="col">Quantity</th>';
            echo '<th scope="col">Price</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            $totalHarga = 0;

            foreach ($orderDetails as $detail) {
                echo '<tr>';
                echo '<td>' . $detail['nama'] . '</td>';
                echo '<td>' . $detail['jumlah'] . '</td>';
                echo '<td>$' . number_format($detail['harga'], 2) . '</td>';
                echo '</tr>';
                $totalHarga += $detail['harga'] * $detail['jumlah'];
            }

            echo '<tr>';
            echo '<td colspan="2" class="text-end fw-bold">Total Harga:</td>';
            echo '<td class="fw-bold">$' . number_format($totalHarga, 2) . '</td>';
            echo '</tr>';

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>This order is empty.</p>';
        }
        ?>
    </div>

    <?php include("./components/footer/footer.php"); ?>
</body>

</html>