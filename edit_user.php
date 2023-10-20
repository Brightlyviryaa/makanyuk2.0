<?php
session_start();

if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin' && isset($_GET['id'])) {
    // Pastikan user sudah login, memiliki peran admin, dan ID pengguna yang akan diubah diberikan
    $user_id = $_GET['id'];

    try {
        // Koneksi ke database
        include("./components/database.php");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle form submission for editing user data
            $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
            $tanggal_lahir = $_POST['tanggal_lahir'];
            $role = $_POST['role'];

            // Check if a new password is provided
            if (!empty($_POST['new_password'])) {
                $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

                // Update user data including the new password
                $updateQuery = "UPDATE users SET email = :email, tanggal_lahir = :tanggal_lahir, role = :role, password = :password WHERE id_user = :id_user";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':tanggal_lahir', $tanggal_lahir);
                $stmt->bindParam(':role', $role);
                $stmt->bindParam(':password', $new_password);
                $stmt->bindParam(':id_user', $user_id);
                $stmt->execute();
            } else {
                // Update user data without changing the password
                $updateQuery = "UPDATE users SET email = :email, tanggal_lahir = :tanggal_lahir, role = :role WHERE id_user = :id_user";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':tanggal_lahir', $tanggal_lahir);
                $stmt->bindParam(':role', $role);
                $stmt->bindParam(':id_user', $user_id);
                $stmt->execute();
            }

            // Redirect to user management page after the update
            header('Location: user.php');
            exit();
        }

        // Query to retrieve the user data for editing
        $query = "SELECT email, tanggal_lahir, role FROM users WHERE id_user = :id_user";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_user', $user_id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Jika pengguna belum login, bukan admin, atau ID pengguna tidak valid, arahkan mereka ke halaman lain
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./components/heading.config.php"); ?>
    <title>Makan Yuk - Edit User</title>
</head>

<body>
    <?php include("./components/navbar/navbar.php"); ?>

    <div class="container mt-5">
        <h2>Edit User</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>"
                    required>
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">New Password (optional)</label>
                <input type="password" class="form-control" id="new_password" name="new_password">
            </div>
            <div class="mb-3">
                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                    value="<?php echo $user['tanggal_lahir']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="admin" <?php if ($user['role'] === 'admin')
                        echo 'selected'; ?>>Admin</option>
                    <option value="user" <?php if ($user['role'] === 'user')
                        echo 'selected'; ?>>User</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>

    <?php include("./components/footer/footer.php"); ?>
</body>

</html>