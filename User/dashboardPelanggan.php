<?php
    session_start();
    include "../Connection/connection.php";
    
    if (!isset($_SESSION['user']) || $_SESSION['level'] != "user") {
        header("Location: ../AMP/Dashboard/dashboard.php");
        exit();
    }

    if($_SESSION['user'] == ""){
        header("location: ../Login/FormLogin.php");
    }


    $sql = "SELECT * from tabel_pelanggan";
    $result = mysqli_query($conn, $sql);
    $jumlah = mysqli_num_rows($result);

    $sql2 = "SELECT * FROM tabel_transaksi INNER JOIN tabel_barang";
    $result2 = mysqli_query($conn, $sql2);  
    $jumlah2 = mysqli_num_rows($result2);

    $sql3 = "SELECT * FROM tabel_barang";
    $result3 = mysqli_query($conn, $sql3);  
    $jumlah3 = mysqli_num_rows($result3);


    // if (isset($_SESSION['user'])) {
    //     echo "nama: " . $_SESSION['user'] . "<br>";
    //     echo "Kode Pelanggan: " . $_SESSION['kode_pelanggan'];
    // } else {
    //     echo "Session belum diatur!";
    // }



?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--BoxIcons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!--Style.CSS-->
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">

    <title>Admin</title>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <!-- Elemen Kiri -->
        <span class="navbar-brand text-white">Koperasi 24</span>

        <!-- Tombol Toggle untuk Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu Utama -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Elemen Tengah -->
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="../User/dashboardPelanggan.php">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../User/Shop/shop.php">Beli Barang</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../User/Shop/History/history.php">History Pembelian</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="../User/Shop/Cart/cart.php">Cart</a>
                </li>
            </ul>

            <!-- Elemen Kanan -->
            <div class="right-nav d-flex align-items-center">
                <span class="navbar-text me-2 text-white"><?php echo ucfirst($_SESSION['user']); ?> ( <?php echo ucfirst($_SESSION['level']); ?> )</span>
                <a href="../Login/Logout.php" class="text-decoration-none text-white">
                    <i class="bx bx-log-out"></i>
                </a>
            </div>
        </div>
    </div>
</nav>

    <!--CONTENT-->
    <section id="content">
        <!--MAIN-->
        <main>
            <div class="home-title text-center">
                <h4>
                    Selamat datang di <span style="color: red;">Koperasi 24</span> 
                    dan Selamat <span style="color:blue;">Berbelanja...</span>
                </h4>
            </div>
        </main>
        <!--MAIN-->
    </section>
    <!--CONTENT-->

    <footer class="bg-body-tertiary text-center text-lg-start">
    <!-- Copyright -->
        <div class="text-center p-3">
            Copyright © 2024 Iftikhar Azhar Chaudhry
        </div>
    <!-- Copyright -->
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.getElementById("myInput");
            const productCards = document.querySelectorAll(".product-card");

            input.addEventListener("keyup", function() {
                const value = input.value.toLowerCase();

                productCards.forEach(function(card) {
                    const productName = card.querySelector("h3").textContent.toLowerCase();
                    card.style.display = productName.includes(value) ? "block" : "none";
                });
            });
        });
    </script>

    <script src="script.js"></script>
</body>
</html>