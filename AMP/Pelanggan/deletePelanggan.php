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

    if(isset($_GET['kode_pelanggan'])) {
        $kodePelanggan = mysqli_real_escape_string($conn, $_GET['kode_pelanggan']);
        $deleteSql = "DELETE FROM tabel_pelanggan WHERE kode_pelanggan = '$kodePelanggan'";
        $deleteQuery = mysqli_query($conn, $deleteSql);
    
        if($deleteQuery) {
            header("Location:../Dashboard/tabelAnggota.php");
            exit;
        } else {
            echo "Gagal menghapus data.";
        }
    }
?>