<?php
    session_start();
    include "../../../Connection/connection.php";

    if (!isset($_SESSION['user']) || $_SESSION['level'] != "user") {
        header("Location: ../../../AMP/Dashboard/dashboard.php");
        exit();
    }
    
    if($_SESSION['user'] == ""){
        header("location: ../../../Login/FormLogin.php");
    }

    // Retrieve cart data from the session
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

    // Fetch item details from the database based on cart data
    $barangKeranjang = [];
    if (!empty($cart)) {
        $kodeBarangList = implode("','", array_keys($cart));
        $sql = "SELECT * FROM tabel_barang WHERE kode_barang IN ('$kodeBarangList')";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $kode = $row['kode_barang'];
            $row['jumlah'] = $cart[$kode]['jumlah']; // Add quantity from session

            // Fetch stok tersisa
            $sqlStok = "SELECT jumlah FROM tabel_barang WHERE kode_barang = '$kode'";
            $resultStok = mysqli_query($conn, $sqlStok);
            $stok = mysqli_fetch_assoc($resultStok);

            $row['stok'] = $stok['jumlah']; // Add stok to array
            $barangKeranjang[] = $row;
        }
        if (isset($_POST['checkout'])) {
            $qry = "SELECT * FROM view_pelanggan WHERE username = '".$_SESSION['user']."'";
            $qry_result = mysqli_query($conn, $qry);
            $result = mysqli_fetch_array($qry_result);
        
            $KodePelanggan = $result['kode_pelanggan'];
            $NamaPelanggan = $result['nama_pelanggan'];
            $TanggalOrder = date('Y-m-d');
        
            $query_transaksi = "INSERT INTO tabel_transaksi VALUES ('','$TanggalOrder', '$KodePelanggan', '$NamaPelanggan')";
            mysqli_query($conn, $query_transaksi);
            $NomorOrder = mysqli_insert_id($conn);
        
            foreach ($_SESSION['cart'] as $kode_barang => $item) {
                // Ambil detail item lengkap dari database untuk memastikan informasi yang benar
                $sqlBarang = "SELECT * FROM tabel_barang WHERE kode_barang = '$kode_barang'";
                $resultBarang = mysqli_query($conn, $sqlBarang);
                $barang = mysqli_fetch_assoc($resultBarang);
        
                $NamaBarang = $barang['nama_barang'];
                $Harga = $barang['harga_jual'];
                $Jumlah = $_POST['jumlah'];
                $SubTotal = $Harga * $Jumlah;
        
                $query_detail = "INSERT INTO transaksi_pelanggan
                                    VALUES ('', '$NomorOrder', '$kode_barang', '$NamaBarang', '$Harga', '$Jumlah', '$SubTotal')";
                mysqli_query($conn, $query_detail);
        
                // Perbarui stok
                $query_update_stok = "UPDATE tabel_barang SET jumlah = jumlah - $Jumlah WHERE kode_barang = '$kode_barang'";
                mysqli_query($conn, $query_update_stok);
            }
        
            unset($_SESSION['cart']);
                echo "<script>alert('Transaksi berhasil!'); window.location.href='../../Shop/shop.php';</script>";
            exit;
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="style.css">

    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        .container {
            flex: 1;
        }

        footer {
            background-color: #b7b1b1;
            text-align: center;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
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

<section class="container py-5">
        <form method="POST">
        <?php if (!empty($barangKeranjang)) { ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header p-3">
                        <h5 class="mb-0">Cart - <?php echo count($barangKeranjang); ?> items</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $totalSemua = 0;
                        foreach ($barangKeranjang as $barang) {
                            $total = $barang['harga_jual'] * $barang['jumlah'];
                            $totalSemua += $total;
                        ?>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5><?php echo $barang['nama_barang']; ?></h5>
                                <p>Jenis : <?php echo ucfirst($barang['jenis_barang']); ?></p>
                                <p>Satuan : <?php echo ucfirst($barang['satuan']); ?></p>
                                <p id="harga-<?php echo $barang['kode_barang']; ?>">Harga: <strong>Rp. <?php echo number_format($barang['harga_jual'], 0, ',', '.'); ?></strong></p>
                                <p id="total-<?php echo $barang['kode_barang']; ?>">Total: <strong>Rp. <?php echo number_format($total, 0, ',', '.'); ?></strong></p>
                                <a href="removeCart.php?kodeBarang=<?php echo $barang['kode_barang']; ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete()">
                                    <i class='bx bx-trash'></i>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group mb-3">
                                    <button class="btn btn-primary" type="button" onclick="updateQty('<?php echo $barang['kode_barang']; ?>', -1)">-</button>
                                        <input type="number" name="jumlah" class="form-control text-center" id="qty-<?php echo $barang['kode_barang']; ?>" value="<?php echo $barang['jumlah']; ?>" min="1" max="<?php echo $barang['stok']; ?>" oninput="updateTotal('<?php echo $barang['kode_barang']; ?>')">
                                    <button class="btn btn-primary" type="button" onclick="updateQty('<?php echo $barang['kode_barang']; ?>', 1)">+</button>
                                </div>
                                <p>Stok Tersisa: <?php echo $barang['stok']; ?></p>
                            </div>
                        </div>
                        <hr>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Summary</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Products</span>
                                <span>Rp. <?php echo number_format($totalSemua, 0, ',', '.'); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Shipping</span>
                                <span>Gratis</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Total</span>
                                <strong id="grandTotal">Rp. <?php echo number_format($totalSemua, 0, ',', '.'); ?></strong>
                            </li>
                        </ul>
                        <form action="" method="post">
                            <button  type="submit" name="checkout" class="btn btn-primary btn-lg btn-block mt-3">Go to Checkout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php } else { ?>
            <center><p>Keranjang belanja Anda kosong😊. Silahkan berbelanja..😁</p></center>
        <?php } ?>
    </form>
    </section>

<footer class="bg-body-tertiary text-center text-lg-start">
    <!-- Copyright -->
        <div class="text-center p-3">
            Copyright © 2024 Iftikhar Azhar Chaudhry
        </div>
    <!-- Copyright -->
    </footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function updateQty(kodeBarang, delta) {
        const qtyInput = document.getElementById(`qty-${kodeBarang}`);
        const newQty = Math.max(1, parseInt(qtyInput.value || 1) + delta);
        if (newQty <= parseInt(qtyInput.max)) {
            qtyInput.value = newQty;
            updateTotal(kodeBarang);
        }
    }

    function updateTotal(kodeBarang) {
        const hargaText = document.getElementById(`harga-${kodeBarang}`).innerText.replace(/[^0-9]/g, '');
        const harga = parseInt(hargaText) || 0;

        const qtyInput = document.getElementById(`qty-${kodeBarang}`);
        const qty = parseInt(qtyInput.value) || 1;

        const total = harga * qty;

        if (!isNaN(total)) {
            document.getElementById(`total-${kodeBarang}`).innerHTML = `Total: <strong>Rp. ${total.toLocaleString('id-ID')}</strong>`;
            updateGrandTotal();
        }
    }

    function updateGrandTotal() {
        let grandTotal = 0;
        const totals = document.querySelectorAll('[id^="total-"]');
        totals.forEach(totalElement => {
            const totalText = totalElement.innerText.replace(/[^0-9]/g, '');
            grandTotal += parseInt(totalText) || 0;
        });
        document.getElementById('grandTotal').innerText = `Rp. ${grandTotal.toLocaleString('id-ID')}`;
    }

    function confirmDelete() {
        return confirm('Apakah Anda yakin ingin menghapus barang ini dari keranjang?');
    }

</script>
</body>
</html>
