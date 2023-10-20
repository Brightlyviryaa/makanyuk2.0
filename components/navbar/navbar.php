<style>
    .ml-auto {
        margin-left: auto;
    }

    .text-bold {
        font-weight: bold;
    }

    .text-color-primary {
        color: #FF6928 !important;
    }

    .disabled {
        color: #FFDDBD !important;
    }

    .bg-body-primary {
        background-color: #FFEBD9;
    }

    .btn-color-primary {
        background-color: #FF6928;
    }

    .pill {
        border-radius: 17px;
    }
</style>

<nav class="navbar navbar-expand-lg bg-body-primary">
    <div class="container-fluid">
        <a class="navbar-brand text-bold text-color-primary" href="#">Makan Yuk</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link active text-color-primary" aria-current="page" href="index.php">Home</a>
                </li>

                <?php
                if (isset($_SESSION['user_id'])) { // Pengguna sudah login
                    if ($_SESSION['user_role'] == 'admin') { // Role admin
                        echo '<li class="nav-item">
                            <a class="nav-link text-color-primary" href="users.php">Users</a>
                        </li>';
                        echo '<li class="nav-item">
                            <a class="nav-link text-color-primary" href="manage_menu.php">Menus</a>
                        </li>';
                        echo '<li class="nav-item">
                            <a class="nav-link text-color-primary" href="manage_order.php">Orders</a>
                        </li>';
                    } elseif ($_SESSION['user_role'] == 'user') { // Role user
                        echo '<li class="nav-item">
                            <a class="nav-link text-color-primary" href="shopping_cart.php">Keranjang</a>
                        </li>';
                        echo '<li class="nav-item">
                            <a class="nav-link text-color-primary" href="order_history.php">History</a>
                        </li>';
                    }

                    echo '<li class="nav-item">
                            <a class="btn btn-color-primary pill" href="logout.php">Logout</a>
                        </li>';
                } else { // Pengguna belum login
                    echo '<li class="nav-item">
                        <a class="btn btn-color-primary pill" href="login.php">Login</a>
                    </li>';
                }
                ?>
            </ul>
        </div>
    </div>
</nav>