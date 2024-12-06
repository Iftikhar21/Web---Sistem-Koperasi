<?php
    session_start();
    include "../../Connection/connection.php";

    if (!isset($_SESSION['user']) || $_SESSION['level'] != "user") {
        header("Location: ../../AMP/Dashboard/dashboard.php");
        exit();
    }
    
    if($_SESSION['user'] == ""){
        header("location: ../../Login/FormLogin.php");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $kode_barang = $data['kode_barang'];
    
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    
        // Tambahkan barang ke keranjang (session)
        if (!in_array($kode_barang, $_SESSION['cart'])) {
            $_SESSION['cart'][] = $kode_barang;
        }
    
        // Kirim respon sukses
        echo json_encode(['success' => true]);
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
                    <a class="nav-link text-white" href="../dashboardPelanggan.php">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../Shop/shop.php">Beli Barang</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../Shop/History/history.php">History Pembelian</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="../Shop/Cart/cart.php">Cart</a>
                </li>
            </ul>

            <!-- Elemen Kanan -->
            <div class="right-nav d-flex align-items-center">
                <span class="navbar-text me-2 text-white"><?php echo ucfirst($_SESSION['user']); ?> ( <?php echo ucfirst($_SESSION['level']); ?> )</span>
                <a href="../../Login/Logout.php" class="text-decoration-none text-white">
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
            <section id="Barang">
                <div class="head-title">
                    <div class="left">
                        <h1>Barang Koperasi 24</h1>
                    </div>
                </div>

                <div class="container-barang">
                    <div class="row d-flex justify-content-center">
                        <input class="form-control title" id="myInput" type="text" placeholder="Cari Barang..">
                    </div>
                    <hr>
                    <div class="product-container">
                        <?php
                            if ($jumlah3 > 0){
                                for($i = 0; $i < $jumlah3; $i++){
                                    $row = mysqli_fetch_array($result3);
                                    if ($row){ ?>
                                            <div class="product-card">
                                                <div class="card-content">
                                                    <h3><?php echo ucfirst($row["nama_barang"])?></h3>
                                                    <p class="price"><?php echo "Rp. " . number_format($row["harga_jual"], 0, ',', '.')?></p>
                                                    <!-- <a href="../Shop/cart.php?kodeBarang=<?=$row["kode_barang"]?>" class="buy-button">Beli</a> -->
                                                    <a href="#" class="buy-button" onclick="addToCart('<?php echo $row['kode_barang']; ?>')">Beli</a>
                                                    <!-- <a href="../Shop/cart.php?kodeBarang=<?php echo $row['kode_barang']; ?>" class="buy-button">Beli</a> -->
                                                </div>
                                            </div>
                                        <?php
                                    }
                                }
                            } else {
                                echo "No products found.";
                            }
                        ?>
                    </div>
                </div>
            </section>
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

        function addToCart(kodeBarang) {
            fetch('../Shop/Cart/add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    kode_barang: kodeBarang,
                    jumlah: 1 // Default quantity is 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message); // Use the message from the server
                } else {
                    alert('Gagal menambahkan barang ke keranjang.');
                }
            })
            .catch(error => console.error('Error:', error));
        }

    </script>

    <script src="script.js"></script>
</body>
</html>