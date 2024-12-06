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

    $sql = "SELECT * FROM tabel_barang";
    $result = mysqli_query($conn, $sql);  
    $jumlah = mysqli_num_rows($result);

    $row = mysqli_fetch_array($result);

    $i = 1;

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
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Custom CSS -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Poppins;
        }

        ::-webkit-scrollbar {
            display: none;
        }

        html, body {
            background-color: #eee;
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }

        .navbar-nav .bx {
            font-size: 24px;
            color: red;
        }
        
        .navbar-brand .bx {
            font-size: 24px;
            color: blueviolet;
            margin-right: 20px;
        }
        
        .navbar {
            position: fixed;
            top: 0px;
            width: 100%;
            padding: 15px 0;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .nav-link {
            color: #333 !important;
            font-weight: 500;
            padding: 8px 16px !important;
        }
        
        .nav-link:hover {
            color: #ff4444 !important;
        }
        
        .search-form {
            position: relative;
        }
        
        .search-form input {
            padding-right: 40px;
            border-radius: 20px;
            border: 1px solid #ddd;
        }
        
        .search-form button {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: none;
            color: #666;
        }
        
        .dark-mode-toggle {
            border: none;
            background: none;
            padding: 8px;
            margin-left: 10px;
        }

        main {
            flex: 1; /* Membuat bagian utama fleksibel */
        }

        main .container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: auto;
            margin: 50px auto;
            padding: 50px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        main .main-table {
            text-align: center;
        }

        main .main-table tr .search {
            background-color: #88C273;
        }

        main .main-table tr .title{
            width: 300px;
            text-align: start;
        }

        main .insert-brg {
            width: 70%;
            margin: 100px auto;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 50px;
        }

        footer {
            text-align: center;
            width: 100%;
        }
        
        footer .text-center { 
            background-color: #b7b1b1;
        }
        
        footer .text-center a {
            text-decoration: none;
        }

    </style>

    <script>
        function fetchBarangData() {
            var kodeBarang = document.querySelector('input[name="kodeBarang"]').value;

            if (kodeBarang !== "") {
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "edit.php?kodeBarang=" + kodeBarang, true);
                xhr.onload = function() {
                    if (this.status === 200) {
                        var data = JSON.parse(this.responseText);

                        if (data) {
                            document.querySelector('input[name="namaBarang"]').value = data.nama_barang;
                            document.querySelector('input[name="jenisBarang"]').value = data.jenis_barang;
                            document.querySelector('input[name="satuanBarang"]').value = data.satuan;
                            document.querySelector('input[name="hargaBeli"]').value = data.harga_beli;
                            document.querySelector('input[name="hargaJual"]').value = data.harga_jual;
                            document.querySelector('input[name="stok"]').value = data.jumlah;
                        } else {
                            // Kosongkan field jika data tidak ditemukan
                            document.querySelector('input[name="namaBarang"]').value = "";
                            document.querySelector('input[name="jenisBarang"]').value = "";
                            document.querySelector('input[name="satuanBarang"]').value = "";
                            document.querySelector('input[name="hargaBeli"]').value = "";
                            document.querySelector('input[name="hargaJual"]').value = "";
                            document.querySelector('input[name="stok"]').value = "";
                        }
                    }
                };
                xhr.send();
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector('input[name="kodeBarang"]').addEventListener("input", fetchBarangData);
        });

            xhr.onload = function() {
            console.log("Response:", this.responseText); // Tambahkan log ini
            if (this.status === 200) {
                var data = JSON.parse(this.responseText);
                if (data) {
                    document.querySelector('input[name="namaBarang"]').value = data.nama_barang;
                    document.querySelector('input[name="jenisBarang"]').value = data.jenis_barang;
                    document.querySelector('input[name="satuanBarang"]').value = data.satuan;
                    document.querySelector('input[name="hargaBeli"]').value = data.harga_beli;
                    document.querySelector('input[name="hargaJual"]').value = data.harga_jual;
                    document.querySelector('input[name="stok"]').value = data.jumlah;
                } else {
                    clearFields(); // Kosongkan field jika tidak ditemukan
                }
            }
        };

        function clearFields() {
            document.querySelector('input[name="namaBarang"]').value = "";
            document.querySelector('input[name="jenisBarang"]').value = "";
            document.querySelector('input[name="satuanBarang"]').value = "";
            document.querySelector('input[name="hargaBeli"]').value = "";
            document.querySelector('input[name="hargaJual"]').value = "";
            document.querySelector('input[name="stok"]').value = "";
        }

    </script>
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
    <div class="insert-brg">
        <div>
            <?php if($_SESSION['level'] == "admin"): ?>
            <a class="btn btn-success" data-toggle="collapse" href="#tambahBarang" role="button" aria-expanded="false" aria-controls="tambahBarang">
                <i class='bx bx-plus'></i>
            </a>
            <label class="col-form-label mx-2" style="font-weight: 600; font-style: italic;">Tambah Barang</label>
            <?php endif; ?>

            <a class="btn btn-success" data-toggle="collapse" href="#tabelBarang" role="button" aria-expanded="false" aria-controls="tabelBarang">
                <i class='bx bx-table'></i>
            </a>
            <label class="col-form-label mx-2" style="font-weight: 600; font-style: italic;">Tabel Barang</label>
        </div>

        <!-- FORM INSERT BARANG -->

        <div class="collapse mt-3" id="tambahBarang">
            <form action="../Barang/Insert & Update/insertAndUpdate.php" method="post">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <label for="kode" class="col-form-label">Kode Barang</label>
                        <input type="text" class="form-control" name="kodeBarang" id="kodeBarang" placeholder="Masukkan Kode Barang" oninput="fetchBarangData()">
                    </div>
                    <div class="col-md-6">
                        <label for="nama" class="col-form-label">Nama Barang</label>
                        <input type="text" class="form-control" name="namaBarang" id="namaBarang" placeholder="Masukkan Nama Barang">
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <label for="jenis" class="col-form-label">Jenis Barang</label>
                        <input type="text" class="form-control" name="jenisBarang" id="jenisBarang" placeholder="Masukkan Jenis Barang">
                    </div>
                    <div class="col-md-6">
                        <label for="nim" class="col-form-label">Satuan Barang</label>
                        <input type="text" class="form-control" name="satuanBarang" id="satuanBarang" placeholder="Masukkan Satuan Barang">
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <label for="nim" class="col-form-label">Harga Beli</label>
                        <input type="number" class="form-control" name="hargaBeli" id="hargaBeli" placeholder="Masukkan Harga Beli">
                    </div>
                    <div class="col-md-6">
                        <label for="nim" class="col-form-label">Harga Jual</label>
                        <input type="number" class="form-control" name="hargaJual" id="hargaJual" placeholder="Masukkan Harga Jual">
                    </div>
                </div>
                <?php if($_SESSION['level'] == "admin"): ?>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
                        <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                    </div>
                </div>
                <?php endif; ?>
            </form>
        </div>

        <!-- TABLE BARANG -->

        <div class="collapse mt-3" id="tabelBarang">
            <div class="main-table">
                <h3 class="mb-3">Tabel Barang</h3>
                <table class="table table-striped table-bordered rounded-3">
                    <tr>
                        <th colspan="8" class="search">
                            <input class="form-control title" id="myInput" type="text" placeholder="Search..">
                        </th>
                    </tr>
                    <tbody id="myTable">
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Jenis Barang</th>
                            <th>Satuan</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                        </tr>
                        <?php do { ?>
                            <tr>
                                <td><?=$i++?></td>
                                <td><?=$row["kode_barang"]?></td>
                                <td><?=$row["nama_barang"]?></td>
                                <td><?=$row["jenis_barang"]?></td>
                                <td><?=$row["satuan"]?></td>
                                <td><?=$row["harga_beli"]?></td>
                                <td><?=$row["harga_jual"]?></td>
                                <?php
                                    if($row["jumlah"] < 5){ ?>
                                        <td><span style="color: red;"><?=$row["jumlah"]?>!</span></td>
                                    <?php } else { ?>
                                        <td><?=$row["jumlah"]?></td>
                                    <?php }
                                ?>
                            </tr>
                            <?php } while ($row = mysqli_fetch_array($result)); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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