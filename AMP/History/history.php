<?php
    session_start();
    include "../../Connection/connection.php";

    if (!isset($_SESSION['user']) || $_SESSION['level'] == "user" || $_SESSION['level'] == "manager") {
        header("Location: ../../User/dashboardPelanggan.php");
        exit();
    }
    
    if($_SESSION['user'] == ""){
        header("location: ../../Login/FormLogin.php");
    }

    // Modifikasi query untuk memastikan semua data transaksi muncul
    $sqlSelectTransaksi = "SELECT
        t.nomor_order,
        t.kode_barang,
        t.nama_barang,
        t.harga_barang,
        t.jumlah_beli,
        t.jumlah_total,
        COALESCE(tt.kode_pelanggan, 'Tidak ada nama') as kode_pelanggan,
        COALESCE(tt.nama_pelanggan, 'Tidak ada nama') as nama_pelanggan,
        COALESCE(tt.tanggal_order, 'Tidak ada tanggal') as tanggal_order
    FROM transaksi_pelanggan t
    LEFT JOIN tabel_transaksi tt ON t.nomor_order = tt.nomor_order
    ORDER BY tt.tanggal_order DESC";
    
    $resultSelectTransaksi = mysqli_query($conn, $sqlSelectTransaksi);
    
    // Debug: Tampilkan jumlah data hasil query
    $numRows = mysqli_num_rows($resultSelectTransaksi);

    $jumlahSelectTransaksi = mysqli_num_rows($resultSelectTransaksi);

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

    <link rel="stylesheet" href="history.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.0/css/boxicons.min.css" integrity="sha512-pVCM5+SN2+qwj36KonHToF2p1oIvoU3bsqxphdOIWMYmgr4ZqD3t5DjKvvetKhXGc/ZG5REYTT6ltKfExEei/Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.css" integrity="sha256-NAxhqDvtY0l4xn+YVa6WjAcmd94NNfttjNsDmNatFVc=" crossorigin="anonymous" />

    
    <!-- Custom CSS -->

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
                <?php if($_SESSION['level'] == "admin" || $_SESSION['level'] == "pemasok" || $_SESSION['level'] == "manager"): ?>
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
                            <?php if($_SESSION['level'] == "manager"): ?>
                                <a class="dropdown-item" href="../Request/purchaseOrder.php">Purchase - Order</a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if($_SESSION['level'] == "admin" || $_SESSION['level'] == "manager"): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Laporan</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../History/history.php">Laporan Transaksi</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../History/historyPurchaseOrder.php">Laporan Restock</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
            
            <form class="search-form d-flex">
                <span class="navbar-text me-2 text-black"><?php echo ucfirst($_SESSION['user']); ?> ( <?php echo ucfirst($_SESSION['level']); ?> )</span>
            </form>

            <ul class="navbar-nav mb-2 mb-lg-0 ms-4">
                <a href="../../Login/Logout.php">
                    <i class='bx bx-log-out'></i>
                </a>
            </ul>
        </div>
    </div>
</nav>

<main>
    <div class="transaction container">
            <h3>Laporan Transaksi</h3>
            <div class="col col-4 d-flex justify-content-center mb-4">
                <input class="form-control title" id="myInput" type="text" placeholder="Cari History..">
        </div>
            <div class="row g-4" id="transactionContainer">
            <?php
                if ($numRows > 0){
                    $counter = 0; // Debug: Hitung berapa card yang di-render
                    while ($row = mysqli_fetch_assoc($resultSelectTransaksi)){
                        $counter++;
                        ?>
                        <div class="mb-4">
                            <div class="card shadow-sm">
                                <div class="card-body">                                    
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <i class='bx bx-user-circle' style="font-size:30px;"></i>
                                        </div>
                                        <div class="flex-1 ms-3">
                                            <h5 class="font-size-16 mb-1"><?php echo htmlspecialchars($row["nama_pelanggan"]);?></h5>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-1">
                                        <div class="mb-1 row align-items-center">
                                            <p class="col-6 text-muted mb-0">Nomor Order</p>
                                            <p class="col-1 text-muted mb-0">:</p>
                                            <p class="col-5 text-muted mb-0"><?php echo htmlspecialchars($row["nomor_order"]);?></p>
                                        </div>
                                        <div class="mb-1 row align-items-center">
                                            <p class="col-6 text-muted mb-0">Tanggal Order</p>
                                            <p class="col-1 text-muted mb-0">:</p>
                                            <p class="col-5 text-muted mb-0"><?php echo htmlspecialchars($row["tanggal_order"]);?></p>
                                        </div>
                                        <div class="mb-1 row align-items-center">
                                            <p class="col-6 text-muted mb-0">Kode Pelanggan</p>
                                            <p class="col-1 text-muted mb-0">:</p>
                                            <p class="col-5 text-muted mb-0"><?php echo htmlspecialchars($row["kode_pelanggan"]);?></p>
                                        </div>
                                        <div class="mb-1 row align-items-center">
                                            <p class="col-6 text-muted mb-0">Kode Barang</p>
                                            <p class="col-1 text-muted mb-0">:</p>
                                            <p class="col-5 text-muted mb-0"><?php echo htmlspecialchars($row["kode_barang"]);?></p>
                                        </div>
                                        <div class="mb-1 row align-items-center">
                                            <p class="col-6 text-muted mb-0">Nama Barang</p>
                                            <p class="col-1 text-muted mb-0">:</p>
                                            <p class="col-5 text-muted mb-0"><?php echo htmlspecialchars($row["nama_barang"]);?></p>
                                        </div>
                                        <div class="mb-1 row align-items-center">
                                            <p class="col-6 text-muted mb-0">Jumlah Beli</p>
                                            <p class="col-1 text-muted mb-0">:</p>
                                            <p class="col-5 text-muted mb-0"><?php echo htmlspecialchars($row["jumlah_beli"]);?></p>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2 pt-2">
                                        <span class="badge text-bg-success p-2"><i class='bx bx-check'></i> Success</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div class='col-12 text-center'><p>Tidak ada transaksi ditemukan.</p></div>";
                }
            ?>
            </div>
    </div>
</main>

<footer class="bg-body-tertiary text-center text-lg-start">
    <!-- Copyright -->
    <div class="text-center p-3">
        Copyright © 2024 Iftikhar Azhar 
    </div>
    <!-- Copyright -->
</footer>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const input = document.getElementById("myInput");
        const cards = document.querySelectorAll(".card"); // Menargetkan semua kartu

        input.addEventListener("keyup", function () {
            const filter = input.value.toLowerCase();

            cards.forEach(function (card) {
                // Ambil semua teks dalam elemen kartu
                const cardText = card.textContent.toLowerCase();

                // Tampilkan atau sembunyikan kartu berdasarkan kecocokan teks
                if (cardText.includes(filter)) {
                    card.style.display = "block"; // Tampilkan jika cocok
                } else {
                    card.style.display = "none"; // Sembunyikan jika tidak cocok
                }
            });
        });
    });
</script>




</body>
</html>