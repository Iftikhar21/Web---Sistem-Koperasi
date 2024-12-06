<?php
    session_start();
    include "../../Connection/connection.php";

    // Cek otentikasi pengguna
    if (!isset($_SESSION['user']) || $_SESSION['user'] == "" || $_SESSION['level'] == "user") {
        header("Location: ../../../User/dashboardPelanggan.php");
        exit();
    }

    // Header untuk JSON response
    header('Content-Type: application/json');

    if (isset($_GET['kodeBarang'])) {
        $kodeBarang = mysqli_real_escape_string($conn, $_GET['kodeBarang']); // Hindari SQL Injection

        $sql = "SELECT * FROM tabel_barang WHERE kode_barang = '$kodeBarang'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_assoc($result);
                echo json_encode($data); // Kembalikan data JSON
            } else {
                echo json_encode(['data' => null]); // Data tidak ditemukan
            }
        } else {
            echo json_encode(['error' => mysqli_error($conn)]); // Error pada query
        }
    } else {
        echo json_encode(['data' => null]); // Jika kodeBarang tidak disediakan
    }
?>
