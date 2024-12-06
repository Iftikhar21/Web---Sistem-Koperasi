<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['level'] != "user") {
    header("Location: ../../../AMP/Dashboard/dashboard.php");
    exit();
}

if($_SESSION['user'] == ""){
    header("location: ../../../Login/FormLogin.php");
}

if (isset($_GET['kodeBarang'])) {
    $kodeBarang = $_GET['kodeBarang'];

    // Hapus barang dari session
    if (isset($_SESSION['cart'][$kodeBarang])) {
        unset($_SESSION['cart'][$kodeBarang]);
    }
}

// Redirect kembali ke halaman cart
header('Location: cart.php');
exit();
?>
