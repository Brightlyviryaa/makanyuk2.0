<?php
session_start();

if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'admin') {
    // Pastikan hanya admin yang dapat mengakses halaman ini

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Handle form submission
        $id_menu = $_POST['id_menu'];
        $nama = $_POST['nama'];
        $deskripsi = $_POST['deskripsi'];
        $harga = $_POST['harga'];

        // Handle file upload
        if ($_FILES['menu_img']['error'] === UPLOAD_ERR_OK) {
            $menu_img = file_get_contents($_FILES['menu_img']['tmp_name']);

            try {
                // Koneksi ke database
                include("./components/database.php");

                // Query untuk mengupdate menu ke database
                $query = "UPDATE Menu
                          SET nama = :nama,
                              deskripsi = :deskripsi,
                              harga = :harga,
                              menu_img = :menu_img
                          WHERE id_menu = :id_menu";

                $stmt = $conn->prepare($query);
                $stmt->bindParam(':id_menu', $id_menu);
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
    } elseif (isset($_GET['id'])) {
        // Tampilkan form untuk mengedit menu
        $id_menu = $_GET['id'];

        try {
            // Koneksi ke database
            include("./components/database.php");

            // Query untuk mengambil data menu berdasarkan ID
            $query = "SELECT * FROM Menu WHERE id_menu = :id_menu";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id_menu', $id_menu);
            $stmt->execute();
            $menu = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
} else {
    // Jika pengguna belum login atau bukan admin, arahkan mereka ke halaman lain
    header('Location: some_other_page.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./components/heading.config.php"); ?>
    <title>Makan Yuk - Edit Menu</title>
</head>

<body>
    <?php include("./components/navbar/navbar.php"); ?>

    <div class="container mt-5">
        <h2>Edit Menu</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_menu" value="<?php echo $id_menu; ?>">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Menu</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $menu['nama']; ?>"
                    required>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                    required><?php echo $menu['deskripsi']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" step="0.01" class="form-control" id="harga" name="harga"
                    value="<?php echo $menu['harga']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="menu_img" class="form-label">Gambar Menu</label>
                <input type="file" class="form-control" id="menu_img" name="menu_img">
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>

    <?php include("./components/footer/footer.php"); ?>
</body>

</html>