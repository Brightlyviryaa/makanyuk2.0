<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    include("./components/heading.config.php");
    ?>
    <title>Makan Yuk - Homepage</title>
    <style>
        .welcome-wrap {
            border-radius: 1rem;
            border: 2px solid black;
            width: 90%;
            max-width: 1170px;
        }

        .w-screen {
            width: 100vw;
        }

        .text-gray {
            color: gray;
        }

        .rounded-carousel .carousel-inner {
            border-radius: 25px;
            max-height: 50vh;
        }

        .rounded-carousel .carousel-item {
            border-radius: 25px;
            overflow: hidden;
        }

        .rounded-carousel .carousel-item img {
            object-fit: cover;
            height: 100%;
            width: 100%;
        }

        .menu-card {
            width: 18rem;
            margin: 1rem;
            border: 1px solid #ccc;
            border-radius: 10px;
            overflow: hidden;
        }

        .menu-img {
            object-fit: cover;
            height: 150px;
            width: 100%;
        }

        .card-body {
            padding: 1rem;
        }
    </style>
</head>

<body>
    <?php include("./components/navbar/navbar.php"); ?>

    <!-- Welcome Section -->
    <div class="container">
        <div id="carouselExampleIndicators" class="carousel slide mt-5 rounded-carousel" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="./components/images/food1.png" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="./components/images/food2.jpeg" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="./components/images/food3.jpeg" class="d-block w-100" alt="...">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon" ariahidden="true"></span>
                <span class="visually-hidden">Next</span>
            </a>
        </div>
    </div>

    <!-- Menu Section -->
    <section class="mt-5">
        <div class="container d-flex flex-column justify-center align-items-center text-center">
            <h1 class="fw-bold">Our Menu</h1>
            <?php
            include("./components/database.php");

            // Mengambil semua data menu dari tabel Menu
            $stmt = $conn->query("SELECT * FROM Menu");
            $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Memeriksa apakah ada menu
            if (count($menus) > 0) {
                echo '<div class="row">';
                foreach ($menus as $menu) {
                    // Check if the user is logged in
                    if (isset($_SESSION['user_id'])) {
                        echo '<div class="col-md-4">';
                        echo '<div class="card menu-card">';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($menu['menu_img']) . '" class="card-img-top menu-img" alt="' . $menu['nama'] . '">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . $menu['nama'] . '</h5>';
                        echo '<p class="card-text">' . $menu['deskripsi'] . '</p>';
                        echo '<p class="card-text">Harga: $' . number_format($menu['harga'], 2) . '</p>';
                        echo '<a href="add_to_cart.php?menu_id=' . $menu['id_menu'] . '" class="btn btn-primary">Tambah ke Keranjang</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    } else {
                        echo '<div class="col-md-4">';
                        echo '<div class="card menu-card">';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($menu['menu_img']) . '" class="card-img-top menu-img" alt="' . $menu['nama'] . '">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . $menu['nama'] . '</h5>';
                        echo '<p class="card-text">' . $menu['deskripsi'] . '</p>';
                        echo '<p class="card-text">Harga: $' . number_format($menu['harga'], 2) . '</p>';
                        echo '<p class="text-danger">Harap login untuk menambahkan ke keranjang.</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
                echo '</div>';
            } else {
                echo '<p>Tidak ada menu yang tersedia saat ini.</p>';
            }
            ?>
        </div>
    </section>
    <?php include("./components/footer/footer.php"); ?>
</body>

</html>