<?php
    session_start();
    include "../../Connection/connection.php";

    if (!isset($_SESSION['user']) || $_SESSION['level'] == "user") {
        header("Location: ../../User/dashboardPelanggan.php");
        exit();
    }
    
    if($_SESSION['user'] == ""){
        header("location: ../../Login/FormLogin.php");
    }

    $badgeCount = 0;
    if ($_SESSION['level'] == "manager") {
        $query = "SELECT COUNT(*) as low_stock FROM tabel_barang WHERE jumlah < 5";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        $badgeCount = $row['low_stock'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Koperasi</title>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">

</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container-sm">
        <a class="navbar-brand" href="#">
            <i class='bx bx-store-alt' ></i>Sistem Koperasi 
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="../Dashboard/dashboard.php">Home</a>
                </li>
                <?php if($_SESSION['level'] == "admin" || $_SESSION['level'] == "manager"): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Master</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../Barang/tabelBarang.php">Barang</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../Pelanggan/tabelanggota.php">Pelanggan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../Pemasok/tabelPemasok.php">Pemasok</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if($_SESSION['level'] == "admin" || $_SESSION['level'] == "manager" || $_SESSION['level'] == "pemasok"): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Transaksi
                        <?php if ($badgeCount > 0): ?>
                            <span class="badge bg-danger rounded-circle"><?php echo $badgeCount; ?></span>
                        <?php endif; ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../Request/purchaseOrder.php">Purchase - Order
                            <?php if ($badgeCount > 0): ?>
                                <span class="badge bg-danger"><?php echo $badgeCount; ?></span>
                            <?php endif; ?>
                        </a></li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if($_SESSION['level'] == "admin" || $_SESSION['level'] == "manager"): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Laporan</a>
                        <ul class="dropdown-menu">
                            <?php if($_SESSION['level'] == "admin"): ?>
                            <li><a class="dropdown-item" href="../History/history.php">Laporan Transaksi</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="../History/historyPurchaseOrder.php">Laporan Restock</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
            
            <form class="search-form d-flex">
                <span class="navbar-text me-2 text-black"><?php echo ucfirst($_SESSION['user']); ?> ( <?php echo ucfirst($_SESSION['level']); ?> )</span>
            </form>

            <ul class="logout navbar-nav mb-2 mb-lg-0 ms-4">
                <a href="../../Login/Logout.php">
                    <i class='bx bx-log-out'></i>
                </a>
            </ul>
        </div>
    </div>
</nav>

<section id="content">
    <main>
    <?php if($_SESSION['level'] == "manager"): ?>
        <!-- Data Barang -->
        <form action="../Request/manager/process.php" method="POST">
            <section id="Barang">
                <div class="container-barang">
                    <div class="row d-flex justify-content-center mb-3">
                        <input class="form-control title" id="myInput" type="text" placeholder="Cari Barang..">
                    </div>
                    <div class="product-container">
                        <?php
                            $query = "SELECT * FROM tabel_barang WHERE jumlah < 5";
                            $result = mysqli_query($conn, $query);
                            $jumlahRow = mysqli_num_rows($result);
                            if ($jumlahRow > 0){
                                for($i = 0; $i < $jumlahRow; $i++){
                                    $row = mysqli_fetch_array($result);
                                    if ($row){ ?>
                                            <div class="product-card">
                                                <div class="card-content">
                                                    <h3><?php echo ucfirst($row["nama_barang"]); ?></h3>
                                                    <div class="d-flex justify-content-center">
                                                        <input type="number" class="form-control text-center" name="jumlah_po[<?php echo $row['kode_barang']; ?>]" min="1" placeholder="Jumlah" required>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                }
                            } else {
                                echo "Stok barang masih mencukupi😊.";
                            }
                        ?>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <button type="submit" class="col-3 btn btn-success">Kirim</button>
                    </div>
                </div>
            </section>
        </form>
        <?php endif; ?>
    </main>
</section>

<footer class="bg-body-tertiary text-center text-lg-start">
    <!-- Copyright -->
    <div class="text-center p-3">
        Copyright © 2024 Iftikhar Azhar Chaudhry
    </div>
    <!-- Copyright -->
</footer>
<!-- Bootstrap JS -->
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

</body>
</html>