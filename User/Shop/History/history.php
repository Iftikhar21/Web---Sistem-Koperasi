<?php
    session_start();
    include "../../../Connection/connection.php";

    if (!isset($_SESSION['user'])) {
        echo "<script>alert('Silakan login terlebih dahulu.'); window.location.href='../Login/login.php';</script>";
        exit;
    }

    $user = $_SESSION['user'];

    // Ambil kode pelanggan dari session
    $qry = "SELECT kode_pelanggan FROM view_pelanggan WHERE username = '$user'";
    $result = mysqli_query($conn, $qry);
    $pelanggan = mysqli_fetch_assoc($result);
    $kodePelanggan = $pelanggan['kode_pelanggan'];

    // Ambil data transaksi pelanggan
    $sqlTransaksi = "SELECT * FROM tabel_transaksi WHERE kode_pelanggan = '$kodePelanggan' ORDER BY tanggal_order DESC";
    $resultTransaksi = mysqli_query($conn, $sqlTransaksi);

    $history = [];
    while ($row = mysqli_fetch_assoc($resultTransaksi)) {
        $nomorOrder = $row['nomor_order'];

        // Ambil detail transaksi
        $sqlDetail = "SELECT * FROM transaksi_pelanggan WHERE nomor_order = '$nomorOrder'";
        $resultDetail = mysqli_query($conn, $sqlDetail);
        $details = [];
        while ($detail = mysqli_fetch_assoc($resultDetail)) {
            $details[] = $detail;
        }

        $row['details'] = $details;
        $history[] = $row;
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Pembelian</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="history.css">
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
                    <a class="nav-link text-white" href="../../dashboardPelanggan.php">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../shop.php">Beli Barang</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../History/history.php">History Pembelian</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="../Cart/cart.php">Cart</a>
                </li>
            </ul>

            <!-- Elemen Kanan -->
            <div class="right-nav d-flex align-items-center">
                <span class="navbar-text me-2 text-white"><?php echo ucfirst($_SESSION['user']); ?> ( <?php echo ucfirst($_SESSION['level']); ?> )</span>
                <a href="../../../Login/Logout.php" class="text-decoration-none text-white">
                    <i class="bx bx-log-out"></i>
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container my-5">
    <h3 class="mb-4">History Pembelian</h3>
    <?php if (!empty($history)) { ?>
        <?php foreach ($history as $transaksi) { ?>
            <div class="card mb-3">
                <div class="card-header">
                    <strong>Nomor Order :</strong> <?php echo $transaksi['nomor_order']; ?> <br>
                    <strong>Tanggal :</strong> <?php echo $transaksi['tanggal_order']; ?>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $totalBayar = 0;
                                foreach ($transaksi['details'] as $detail) {
                                        $subtotal = $detail['jumlah_beli'] * $detail['harga_barang'];
                                        $totalBayar += $subtotal; // Tambahkan subtotal ke total bayar
                                    ?>
                                <tr>
                                    <td><?php echo $detail['nama_barang']; ?></td>
                                    <td><?php echo $detail['jumlah_beli']; ?></td>
                                    <td>Rp <?php echo number_format($detail['harga_barang'], 2, ',', '.'); ?></td>
                                    <td>Rp <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="jmlTtl">Jumlah Total</td>
                                <td>Rp 
                                    <?php 
                                        echo number_format($totalBayar, 2, ',', '.'); 
                                    ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p>Belum ada history pembelian.</p>
    <?php } ?>
</div>

<footer class="bg-body-tertiary text-center text-lg-start">
    <div class="text-center p-3">
        Copyright © 2024 Iftikhar Azhar Chaudhry
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
