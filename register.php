<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    include("./components/heading.config.php");
    include("./components/database.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $tanggal_lahir = $_POST['tanggal_lahir'];
        $role = 'user'; // Default role adalah 'user'
    
        $stmt = $conn->prepare("INSERT INTO users (email, password, tanggal_lahir, role) VALUES (:email, :password, :tanggal_lahir, :role)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':tanggal_lahir', $tanggal_lahir);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            // Pendaftaran berhasil, arahkan ke halaman login
            header("Location: login.php");
            exit();
        } else {
            $register_error = "Pendaftaran gagal. Mohon coba lagi.";
        }
    }
    ?>
    <title>Makan Yuk - Register</title>
</head>

<body>
    <?php include("./components/navbar/navbar.php"); ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">Daftar Akun</h2>
                        <?php
                        if (isset($register_error)) {
                            echo '<div class="alert alert-danger">' . $register_error . '</div>';
                        }
                        ?>
                        <form action="register.php" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary">Daftar</button>
                        </form>
                        <p class="mt-3">Sudah punya akun? <a href="login.php">Login di sini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include("./components/footer/footer.php"); ?>
</body>

</html>