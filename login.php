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
        $captcha = $_POST['captcha']; // Tambahkan input captcha
    
        // Periksa apakah captcha benar
        $captchaSession = isset($_SESSION['captcha']) ? $_SESSION['captcha'] : '';
        if ($captcha != $captchaSession) {
            $login_error = "Captcha salah. Silakan coba lagi.";
        } else {
            $stmt = $conn->prepare("SELECT id_user, email, password, role FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Login berhasil, simpan informasi pengguna ke sesi
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];

                // Redirect ke halaman lain setelah login
                header("Location: index.php"); // Ganti welcome.php dengan halaman yang sesuai
                exit();
            } else {
                $login_error = "Login gagal. Periksa kembali email dan kata sandi Anda.";
            }
        }
    }

    // Fungsi untuk membuat captcha sederhana
    function generateCaptcha($length = 5)
    {
        $characters = '0123456789';
        $captcha = '';
        for ($i = 0; $i < $length; $i++) {
            $captcha .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $captcha;
    }

    // Buat captcha dan simpan di sesi
    $captcha = generateCaptcha();
    $_SESSION['captcha'] = $captcha;
    ?>
    <title>Makan Yuk - Login</title>
</head>

<body>
    <?php include("./components/navbar/navbar.php"); ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">Login</h2>
                        <?php
                        if (isset($login_error)) {
                            echo '<div class="alert alert-danger">' . $login_error . '</div>';
                        }
                        ?>
                        <form action="login.php" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <!-- Tambahkan input captcha -->
                            <div class="mb-3">
                                <label for="captcha" class="form-label">Captcha</label>
                                <input type="text" class="form-control" id="captcha" name="captcha" required>
                            </div>
                            <img src="captcha_image.php" alt="Captcha Image">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                        <p class="mt-3">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include("./components/footer/footer.php"); ?>
</body>

</html>