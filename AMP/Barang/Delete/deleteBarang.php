<?php
    include "../../Connection/connection.php";

    if (!isset($_SESSION['user']) || $_SESSION['level'] == "user" || $_SESSION['level'] == "manager") {
        header("Location: ../../../User/dashboardPelanggan.php");
        exit();
    }
    
    if($_SESSION['user'] == ""){
        header("location: ../../../Login/FormLogin.php");
    }

    if (isset($_POST['delete'])) {
        // Logika untuk Delete
        $kodeBarang = $_POST['kodeBarang'];

        $deleteBarangSql = "DELETE FROM tabel_barang WHERE kode_barang = '$kodeBarang'";

        if (mysqli_query($conn, $deleteBarangSql)) {
            header("Location: ../tabelBarang.php");
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
?>