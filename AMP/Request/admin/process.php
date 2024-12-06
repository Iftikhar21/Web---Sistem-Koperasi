<?php
    session_start();
    include "../../../Connection/connection.php";

    if (!isset($_SESSION['user']) || $_SESSION['level'] == "user" || $_SESSION['level'] == "manager" || $_SESSION['level'] == "pemasok") {
        header("Location: ../../../User/dashboardPelanggan.php");
        exit();
    }
    
    if($_SESSION['user'] == ""){
        header("location: ../../../Login/FormLogin.php");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_transaksi = $_POST['id_transaksi'];
        $action = $_POST['action'];
        $approve_by = $_SESSION['user']; // Nama manager yang login
    
        if ($action == 'approve') {
            $status = 'Requested';
        } else {
            $status = 'Rejected';
        }
    
        // Update status transaksi
        $query = "UPDATE transaksi_koperasi SET status = '$status', approve_by = '$approve_by' WHERE id_transaksi_kop = '$id_transaksi'";
        mysqli_query($conn, $query);
    
        header("Location: process.php");
    }

    $queryCheck = "SELECT * FROM transaksi_koperasi WHERE status = 'Pending'";
    $resultCheck = mysqli_query($conn, $queryCheck);

    if (!$resultCheck) {
        die("Query Error: " . mysqli_error($conn)); // Debugging query
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
                    <a class="nav-link" href="../../Dashboard/dashboard.php">Home</a>
                </li>
                <?php if($_SESSION['level'] == "admin" || $_SESSION['level'] == "manager"): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Master</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../../Barang/tabelBarang.php">Barang</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../../Pelanggan/tabelanggota.php">Pelanggan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../../Pemasok/tabelPemasok.php">Pemasok</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if($_SESSION['level'] == "admin" || $_SESSION['level'] == "manager" || $_SESSION['level'] == "pemasok"): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Transaksi</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../../Request/purchaseOrder.php">Purchase - Order</a></li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if($_SESSION['level'] == "admin" || $_SESSION['level'] == "manager"): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Laporan</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../../History/history.php">Laporan Transaksi</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../../History/historyPurchaseOrder.php">Laporan Restock</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
            
            <form class="search-form d-flex">
                <span class="navbar-text me-2 text-black"><?php echo ucfirst($_SESSION['user']); ?> ( <?php echo ucfirst($_SESSION['level']); ?> )</span>
            </form>

            <ul class="logout navbar-nav mb-2 mb-lg-0 ms-4">
                <a href="../../../Login/Logout.php">
                    <i class='bx bx-log-out'></i>
                </a>
            </ul>
        </div>
    </div>
</nav>

<main>
    <div class="container">
        <!-- Data Barang -->
        <form action="" method="POST">
        <h4>Update Stok Barang</h4>
        <?php 
            if (mysqli_num_rows($resultCheck) > 0) {
                while ($row = mysqli_fetch_assoc($resultCheck)) { ?>
                    <div class="mb-4">
                        <hr>
                        <div class="mb-3 row align-items-center mt-4">
                            <div class="col-sm-9">
                                <input type='hidden' class="form-control text-start" name='id_transaksi' value='<?php echo $row['id_transaksi_kop']; ?>'>
                            </div>  
                        </div>
                        <div class="mb-3 row d-flex justify-content-center mt-4">
                            <div class="col-sm-9">
                                <input type='text' class="form-control text-start" name='nama_barang' value='<?php echo $row['nama_barang']; ?>'>
                            </div>  
                        </div>
                        <div class="mb-3 row d-flex justify-content-center mt-4">
                            <div class="col-sm-9">
                                <input type='text' class="form-control text-start" name='jumlah' value='<?php echo $row['jumlah']; ?>'>
                            </div>  
                        </div>
                        <div class="button-container">
                            <button type='submit' class="btn btn-success" name='action' value='approve'>Setujui</button>
                            <button type='submit' class="btn btn-danger" name='action' value='reject'>Tolak</button>
                        </div>
                    </div>
                <?php 
                }
            } else { ?>
                <div class='text-center mt-4'>Tidak ada Request Stok lagi dari Manager.</div>
            <?php }
        ?>
        </form>
    </div>
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
