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

    $sql = "SELECT * from tabel_pelanggan";
    $result = mysqli_query($conn, $sql);
    $jumlah = mysqli_num_rows($result);

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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">

    <style>
        body {
            background-color: #eee;
        }

        main .main-table {
            width: 90%;
            padding: 50px;
            text-align: center;
        }

        main .main-table tr .title{
            width: 300px;
            text-align: start;
        }
    </style>

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

            <ul class="navbar-nav mb-2 mb-lg-0 ms-4">
                <a href="../../Login/Logout.php">
                    <i class='bx bx-log-out'></i>
                </a>
            </ul>
        </div>
    </div>
</nav>

<main>
<div class="main-table">
    <h3 class="mb-3">Tabel Pelanggan</h3>
    <table class="table table-striped table-bordered">
        <tr>
            <th colspan="6" class="search">
                <input class="form-control title" id="myInput" type="text" placeholder="Search..">
            </th>
        </tr>
        <tbody id="myTable">
            <tr>
                <th>No</th>
                <th>Kode Pelanggan</th>
                <th>Nama Pelanggan</th>
                <th>Alamat Pelanggan</th>
                <th>No Telepon</th>
                <?php if($_SESSION['level'] == "admin"): ?>
                    <th>Action</th>
                <?php endif; ?>
            </tr>
            <?php
                for($i = 0; $i < $jumlah; $i++){
                    $row = mysqli_fetch_array($result);
                    ?>
                    <tr>
                        <td><?=$i + 1?></td>
                        <td><?=$row["kode_pelanggan"]?></td>
                        <td><?=$row["nama_pelanggan"]?></td>
                        <td><?=$row["alamat_pelanggan"]?></td>
                        <td><?=$row["no_telp_pelanggan"]?></td>
                        <?php if($_SESSION['level'] == "admin"): ?>
                            <td>
                                <a href="../Pelanggan/formUpdatePelanggan.php?kode_pelanggan=<?= $row["kode_pelanggan"] ?>" name="update" class="btn btn-success">Update</a>
                                <a href="../Pelanggan/deletePelanggan.php?kode_pelanggan=<?= $row["kode_pelanggan"] ?>" name="delete" class="btn btn-danger">Delete</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                    <?php
                }
            ?>
        </tbody>
    </table>
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

<script>
    $(document).ready(function(){
    $("#myInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#myTable tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    });
</script>

</body>
</html>