<?php
session_start();

if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'admin') {
    // Pastikan hanya admin yang dapat mengakses halaman ini

    try {
        // Koneksi ke database
        include("./components/database.php");

        // Query untuk mengambil semua data pengguna
        $query = "SELECT * FROM users";
        $stmt = $conn->query($query);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Jika pengguna belum login atau bukan admin, arahkan mereka ke halaman lain
    header('Location: ./login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./components/heading.config.php"); ?>
    <title>Makan Yuk - User Management</title>
    <style>
        .table {
            width: 100%;
        }
    </style>
</head>

<body>
    <?php include("./components/navbar/navbar.php"); ?>

    <div class="container mt-5">
        <h2>User Management</h2>
        <?php
        if (count($users) > 0) {
            echo '<table class="table table-bordered mt-4">';
            echo '<thead>';
            echo '<tr>';
            echo '<th scope="col">ID</th>';
            echo '<th scope="col">Email</th>';
            echo '<th scope="col">Tanggal Lahir</th>';
            echo '<th scope="col">Role</th>';
            echo '<th scope="col">Actions</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            foreach ($users as $user) {
                echo '<tr>';
                echo '<td>' . $user['id_user'] . '</td>';
                echo '<td>' . $user['email'] . '</td>';
                echo '<td>' . $user['tanggal_lahir'] . '</td>';
                echo '<td>' . $user['role'] . '</td>';
                echo '<td>
                    <a href="edit_user.php?id=' . $user['id_user'] . '" class="btn btn-primary">Edit</a>
                    <a href="./admin/delete_user.php?id=' . $user['id_user'] . '" class="btn btn-danger">Delete</a>
                </td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>No users found.</p>';
        }
        ?>
    </div>

    <?php include("./components/footer/footer.php"); ?>
</body>

</html>