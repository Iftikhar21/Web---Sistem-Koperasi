<?php
    session_start();
    include "../../Connection/connection.php";

    // Redirect jika pengguna tidak sesuai level
    if (!isset($_SESSION['user']) || $_SESSION['level'] == "user") {
        header("Location: ../../User/dashboardPelanggan.php");
        exit();
    }

    if ($_SESSION['user'] == "") {
        header("location: ../../Login/FormLogin.php");
    }

    // Notifikasi stok rendah untuk manager
    if ($_SESSION['level'] == "manager" && !isset($_SESSION['low_stock_notified'])) {
        $notifications = [];
        $query = "SELECT * FROM tabel_barang WHERE jumlah < 5";
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            $notifications[] = "Stok barang {$row['nama_barang']} menipis (Sisa: {$row['jumlah']})!";
        }

        if (!empty($notifications)) {
            $message = implode("\\n", $notifications);
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    alert('$message');
                });
            </script>";
            $_SESSION['low_stock_notified'] = true;
        }
    }

    // Hitung badge untuk notifikasi stok rendah
    $badgeCount = 0;
    $query = "SELECT * FROM tabel_barang WHERE jumlah < 5";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $badgeCount++;
    }

    $jumlahPelangganSql = "SELECT COUNT(*) AS jumlah_kolom FROM tabel_pelanggan;";
    $result = $conn->query($jumlahPelangganSql);
    $jumlahPelanggan = $result->fetch_assoc()['jumlah_kolom'];

    $jumlahBrgSql = "SELECT COUNT(*) AS jumlah_kolom FROM tabel_barang;";
    $resultBrg = $conn->query($jumlahBrgSql);
    $jumlahBrg = $resultBrg->fetch_assoc()['jumlah_kolom'];

    // Fungsi untuk menghitung pemasukan
    function getPemasukan($conn) {
        $query = "SELECT SUM(jumlah_total) AS total_pemasukan FROM transaksi_pelanggan";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total_pemasukan'] ?: 0;
    }

    // Fungsi untuk menghitung pengeluaran
    function getPengeluaran($conn) {
        $query = "
            SELECT SUM(tb.harga_beli * tk.jumlah) AS total_pengeluaran 
            FROM transaksi_koperasi tk
            JOIN tabel_barang tb ON tk.kode_barang = tb.kode_barang
            WHERE tk.status = 'approved'
        ";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total_pengeluaran'] ?: 0;
    }

    // Hitung total pemasukan, pengeluaran, dan total bersih
    $pemasukan = getPemasukan($conn);
    $pengeluaran = getPengeluaran($conn);
    $totalBersih = $pemasukan - $pengeluaran;
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
    <link rel="stylesheet" href="dashboard.css">

</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class='bx bx-store-alt' ></i>Sistem Koperasi 
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if($_SESSION['level'] == "admin" || $_SESSION['level'] == "manager"): ?>
                <li class="nav-item">
                    <a class="nav-link" href="../Dashboard/dashboard.php">Home</a>
                </li>
                <?php endif; ?>
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
                <?php if($_SESSION['level'] == "manager"): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Transaksi
                        <?php if ($badgeCount > 0): ?>
                            <span class="badge bg-danger rounded-circle"><?php echo $badgeCount; ?></span>
                        <?php endif; ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <?php if($_SESSION['level'] == "manager"): ?>
                            <a class="dropdown-item" href="../Request/purchaseOrder.php">Purchase - Order
                                <?php if ($badgeCount > 0): ?>
                                    <span class="badge bg-danger"><?php echo $badgeCount; ?></span>
                                <?php endif; ?>
                            </a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if($_SESSION['level'] == "admin" || $_SESSION['level'] == "pemasok"): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Transaksi</a>
                    <ul class="dropdown-menu">
                        <li>
                            <?php if($_SESSION['level'] == "admin"): ?>
                                <a class="dropdown-item" href="../Request/admin/process.php">Purchase - Order</a>
                            <?php endif; ?>
                            <?php if($_SESSION['level'] == "pemasok"): ?>
                                <a class="dropdown-item" href="../Request/pemasok/process.php">Purchase - Order</a>
                            <?php endif; ?>
                        </li>
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

<main>
    <?php if($_SESSION['level'] == "admin" || $_SESSION['level'] == "manager"): ?>
        <ul class="box-info">
            <li>
                <i class='bx bxs-user-circle'></i>
                <span class="text">
                    <h3><?= $jumlahPelanggan ?></h3>
                    <p>Pelanggan</p>
                </span>
            </li>
            <li>
                <i class='bx bxs-wallet'></i>
                <span class="text">
                    <h3><span style="color: green;">+ Rp <?php echo number_format($pemasukan, 2, ',', '.'); ?></span></h3>
                    <p>Pemasukkan</p>
                </span>
            </li>
            <li>
                <i class='bx bxs-minus-circle'></i>
                <span class="text">
                    <h3><span style="color: red;">- Rp <?php echo number_format($pengeluaran, 2, ',', '.'); ?></span></h3>
                    <p>Pengeluaran</p>
                </span>
            </li>
            <li>
                <i class='bx bxs-dollar-circle'></i>
                <span class="text">
                    <h3><span style="color: blue;">+ Rp <?php echo number_format($totalBersih, 2, ',', '.'); ?></span></h3>
                    <p>Total Bersih</p>
                </span>
            </li>
        </ul>
        <ul class="box-info">
            <li>
                <i class='bx total-barang bxs-package'></i>
                <span class="text">
                    <h3><?= $jumlahBrg ?></h3>
                    <p>Total Barang</p>
                </span>
            </li>
        </ul>
    <?php endif; ?>
</main>
<footer class="bg-body-tertiary text-center text-lg-start">
    <!-- Copyright -->
    <div class="text-center p-3">
        Copyright © 2024 Iftikhar Azhar Chaudhry
    </div>
    <!-- Copyright -->
</footer>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>