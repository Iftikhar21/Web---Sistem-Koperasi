<?php
session_start();
include "../../Connection/connection.php";

if (!isset($_SESSION['user']) || $_SESSION['level'] != "user") {
    header("Location: ../../../AMP/Dashboard/dashboard.php");
    exit();
}

if($_SESSION['user'] == ""){
    header("location: ../../../Login/FormLogin.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM tabel_pelanggan WHERE nama_pelanggan = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // Simpan informasi ke dalam session
        $_SESSION['user'] = $row['nama_pelanggan'];
        $_SESSION['kode_pelanggan'] = $row['kode_pelanggan'];

        // Redirect ke halaman dashboard
        header("Location: ../../dashboardPelanggan.php");
        exit();
    } else {
        echo "Login gagal: Username atau password salah!";
    }
}
?>


