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

    if(isset($_GET['kode_pemasok'])) {
        $kodePemasok = mysqli_real_escape_string($conn, $_GET['kode_pemasok']);
        $deleteSql = "DELETE FROM tabel_pemasok WHERE kode_pemasok = '$kodePemasok'";
        $deleteQuery = mysqli_query($conn, $deleteSql);
    
        if($deleteQuery) {
            header("Location:../Pemasok/tabelPemasok.php");
            exit;
        } else {
            echo "Gagal menghapus data.";
        }
    }
?>