<?php
session_start();

if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'admin') {
    // Pastikan hanya admin yang dapat mengakses halaman ini

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Handle form submission
        $nama = $_POST['nama'];
        $deskripsi = $_POST['deskripsi'];
        $harga = $_POST['harga'];

        // Handle file upload
        if ($_FILES['menu_img']['error'] === UPLOAD_ERR_OK) {
            $menu_img = file_get_contents($_FILES['menu_img']['tmp_name']);

            try {
                // Koneksi ke database
                include("./components/database.php");

                // Query untuk menyimpan menu baru ke database
                $query = "INSERT INTO Menu (nama, deskripsi, harga, menu_img) VALUES (:nama, :deskripsi, :harga, :menu_img)";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':nama', $nama);
                $stmt->bindParam(':deskripsi', $deskripsi);
                $stmt->bindParam(':harga', $harga);
                $stmt->bindParam(':menu_img', $menu_img, PDO::PARAM_LOB);
                $stmt->execute();

                // Redirect to menu management page
                header('Location: manage_menu.php');
                exit();
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
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
    <title>Makan Yuk - Add Menu</title>
</head>

<body>
    <?php include("./components/navbar/navbar.php"); ?>

    <div class="container mt-5">
        <h2>Add Menu</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Menu</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" step="0.01" class="form-control" id="harga" name="harga" required>
            </div>
            <div class="mb-3">
                <label for="menu_img" class="form-label">Gambar Menu</label>
                <input type="file" class="form-control" id="menu_img" name="menu_img" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>

    <?php include("./components/footer/footer.php"); ?>
</body>

</html>