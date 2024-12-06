<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['level'] != "user") {
    header("Location: ../../../AMP/Dashboard/dashboard.php");
    exit();
}

if($_SESSION['user'] == ""){
    header("location: ../../../Login/FormLogin.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $kode_barang = $data['kode_barang'];
    $jumlah = $data['jumlah'];

    // Inisialisasi keranjang jika belum ada
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Tambahkan barang ke dalam keranjang
    if (isset($_SESSION['cart'][$kode_barang])) {
        // Jika barang sudah ada, tambahkan jumlahnya
        $_SESSION['cart'][$kode_barang]['jumlah'] += $jumlah;
    } else {
        // Jika barang belum ada, tambahkan baru
        $_SESSION['cart'][$kode_barang] = [
            'kode_barang' => $kode_barang,
            'jumlah' => $jumlah
        ];
    }

    echo json_encode(['success' => true, 'message' => 'Barang berhasil ditambahkan ke keranjang']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
