<?php
session_start();

if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'admin' && isset($_GET['id'])) {
    // Pastikan hanya admin yang dapat mengakses halaman ini

    $order_id = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Handle jika form dropdown dipost

        if (isset($_POST['status_order'])) {
            $newStatus = $_POST['status_order'];

            try {
                // Koneksi ke database
                include("./components/database.php");

                // Query untuk mengambil tanggal pesanan
                $orderDateQuery = "SELECT tanggal_order FROM `Order` WHERE id_order = :order_id";
                $orderDateStmt = $conn->prepare($orderDateQuery);
                $orderDateStmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
                $orderDateStmt->execute();
                $orderDateResult = $orderDateStmt->fetch(PDO::FETCH_ASSOC);
                $orderDate = $orderDateResult['tanggal_order'];

                // Query untuk mengupdate status pesanan
                $updateStatusQuery = "UPDATE `Order` SET status_order = :newStatus, tanggal_order = :orderDate WHERE id_order = :order_id";
                $updateStatusStmt = $conn->prepare($updateStatusQuery);
                $updateStatusStmt->bindParam(':newStatus', $newStatus, PDO::PARAM_STR);
                $updateStatusStmt->bindParam(':orderDate', $orderDate, PDO::PARAM_STR);
                $updateStatusStmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
                $updateStatusStmt->execute();
            } catch (PDOException $e) {
                echo "Error updating status: " . $e->getMessage();
            }
        }

    }

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
        $orderInfoQuery = "SELECT o.id_order, u.email AS user_email, o.tanggal_order, o.status_order, o.cara_pembayaran
                           FROM `Order` o
                           INNER JOIN users u ON o.id_user = u.id_user
                           WHERE o.id_order = :order_id";
        $orderInfoStmt = $conn->prepare($orderInfoQuery);
        $orderInfoStmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $orderInfoStmt->execute();
        $orderInfo = $orderInfoStmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Jika pengguna belum login, bukan admin, atau ID pesanan tidak valid, arahkan mereka ke halaman lain
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./components/heading.config.php"); ?>
    <title>Makan Yuk - View Order</title>
    <style>
        .table {
            width: 100%;
        }
    </style>
</head>

<body>
    <?php include("./components/navbar/navbar.php"); ?>

    <div class="container mt-5">
        <h2>View Order</h2>
        <p>Order ID:
            <?php echo $order_id; ?>
        </p>
        <p>User Email:
            <?php echo $orderInfo['user_email']; ?>
        </p>
        <p>Order Date:
            <?php echo $orderInfo['tanggal_order']; ?>
        </p>
        <form method="post">
            <p>Status Pesanan:
                <select name="status_order">
                    <option value="pending" <?php if ($orderInfo['status_order'] === 'pending')
                        echo 'selected'; ?>>
                        Pending</option>
                    <option value="diterima" <?php if ($orderInfo['status_order'] === 'diterima')
                        echo 'selected'; ?>>
                        Diterima</option>
                    <option value="dimasak" <?php if ($orderInfo['status_order'] === 'dimasak')
                        echo 'selected'; ?>>
                        Dimasak</option>
                    <option value="diantar" <?php if ($orderInfo['status_order'] === 'diantar')
                        echo 'selected'; ?>>
                        Diantar</option>
                    <option value="success" <?php if ($orderInfo['status_order'] === 'success')
                        echo 'selected'; ?>>
                        Success</option>
                </select>
                <button type="submit">Ubah Status</button>
            </p>
        </form>
        <p>Cara Pembayaran:
            <?php echo $orderInfo['cara_pembayaran']; ?>
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