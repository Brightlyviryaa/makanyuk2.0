<?php
session_start();

if (isset($_SESSION['user_id'])) {
    // Pastikan user sudah login
    $user_id = $_SESSION['user_id'];

    try {
        // Koneksi ke database
        include("./components/database.php");

        // Query untuk mengambil data menu dan gambar dari tabel Menu
        $query = "SELECT m.id_menu, m.nama, m.deskripsi, m.harga, m.menu_img, s.jumlah
                    FROM Shopping_Cart s
                    INNER JOIN Menu m ON s.id_menu = m.id_menu
                    WHERE s.id_user = :user_id";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);


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
    <title>Makan Yuk - Shopping Cart</title>
    <style>
        .card img {
            object-fit: cover;
            height: 200px;
            width: 100%;
        }
    </style>
</head>

<body>
    <?php include("./components/navbar/navbar.php"); ?>

    <div class="container mt-5">
        <h2>Your Shopping Cart</h2>
        <?php
        if (count($cart_items) > 0) {
            // Menampilkan menu dalam keranjang belanja dengan Bootstrap cards
            foreach ($cart_items as $cart_item) {
                echo '<div class="card mb-3">';
                echo '<div class="row g-0">';
                echo '<div class="col-md-4">';
                echo '<img src="data:image/jpeg;base64,' . base64_encode($cart_item['menu_img']) . '" class="img-fluid rounded-start menu-img" alt="' . $cart_item['nama'] . '">';
                echo '</div>';
                echo '<div class="col-md-8">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $cart_item['nama'] . '</h5>';
                echo '<p class="card-text">' . $cart_item['deskripsi'] . '</p>';
                echo '<p class="card-text">Harga: $' . number_format($cart_item['harga'], 2) . '</p>';
                echo '<p class="card-text">Jumlah: ' . $cart_item['jumlah'] . '</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            // Menghitung total harga
            $total_harga = 0;

            if (count($cart_items) > 0) {
                foreach ($cart_items as $cart_item) {
                    $harga = $cart_item['harga'];
                    $jumlah = $cart_item['jumlah'];
                    $total_harga += ($harga * $jumlah);
                }
            }

            echo '<div class="table-responsive mt-4">';
            echo '<table class="table table-bordered">';
            echo '<thead>';
            echo '<tr>';
            echo '<th scope="col">Total Harga</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '<tr>';
            echo '<td>$' . number_format($total_harga, 2) . '</td>';
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';
            echo '</div>';

            // Tombol "Order Now"
            echo '<div class="mt-4">';
            echo '<a href="order.php" class="btn btn-primary">Order Now</a>';
            echo '</div>';
        } else {
            echo '<p>Your shopping cart is empty.</p>';
        }
        ?>
    </div>


    <?php include("./components/footer/footer.php"); ?>
</body>

</html>