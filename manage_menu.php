<?php
session_start();

if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'admin') {
    // Pastikan hanya admin yang dapat mengakses halaman ini

    try {
        // Koneksi ke database
        include("./components/database.php");

        // Query untuk mengambil semua data menu
        $query = "SELECT * FROM Menu";
        $stmt = $conn->query($query);
        $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Makan Yuk - Manage Menu</title>
    <style>
        .table {
            width: 100%;
        }
    </style>
</head>

<body>
    <?php include("./components/navbar/navbar.php"); ?>

    <div class="container mt-5">
        <h2>Manage Menu</h2>
        <a href="add_menu.php" class="btn btn-primary">Tambah Menu</a>
        <?php
        if (count($menus) > 0) {
            echo '<table class="table table-bordered mt-4">';
            echo '<thead>';
            echo '<tr>';
            echo '<th scope="col">ID</th>';
            echo '<th scope="col">Nama Menu</th>';
            echo '<th scope="col">Deskripsi</th>';
            echo '<th scope="col">Harga</th>';
            echo '<th scope="col">Actions</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            foreach ($menus as $menu) {
                echo '<tr>';
                echo '<td>' . $menu['id_menu'] . '</td>';
                echo '<td>' . $menu['nama'] . '</td>';
                echo '<td>' . $menu['deskripsi'] . '</td>';
                echo '<td>$' . number_format($menu['harga'], 2) . '</td>';
                echo '<td>
                    <a href="edit_menu.php?id=' . $menu['id_menu'] . '" class="btn btn-primary">Edit</a>
                    <a href="admin/delete_menu.php?id=' . $menu['id_menu'] . '" class="btn btn-danger">Delete</a>
                </td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>No menu found.</p>';
        }
        ?>
    </div>

    <?php include("./components/footer/footer.php"); ?>
</body>

</html>