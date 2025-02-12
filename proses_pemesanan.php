<?php
include "koneksi.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data produk dan order quantity
    $produk_id = $_POST['ProdukID'];
    $orderQty  = (int) $_POST['order_qty'];

    // Ambil data produk untuk mendapatkan stok dan harga
    $query = "SELECT Stok, Harga FROM produk WHERE ProdukID='$produk_id'";
    $result = mysqli_query($konek, $query);
    if (!$result || mysqli_num_rows($result) == 0) {
        echo "Produk tidak ditemukan.";
        exit;
    }
    $data = mysqli_fetch_assoc($result);
    $stokAvailable = (int) $data['Stok'];

    // Cek apakah stok cukup untuk pesanan
    if ($orderQty > $stokAvailable) {
        echo "Stok tidak mencukupi. Pesanan gagal diproses.";
        exit;
    }

    // Hitung total harga (tanpa memerlukan tombol hitung)
    $totalHarga = $orderQty * $data['Harga'];

    // Simpan data pelanggan ke tabel pelanggan
    $NamaPelanggan = mysqli_real_escape_string($konek, $_POST['NamaPelanggan']);
    $Alamat        = mysqli_real_escape_string($konek, $_POST['Alamat']);
    $NomorTelepon  = mysqli_real_escape_string($konek, $_POST['NomorTelepon']);

    $insertPelanggan = "INSERT INTO pelanggan (NamaPelanggan, Alamat, NomorTelepon) 
                        VALUES ('$NamaPelanggan', '$Alamat', '$NomorTelepon')";
    $resultPelanggan = mysqli_query($konek, $insertPelanggan);
    if (!$resultPelanggan) {
        echo "Gagal menyimpan data pelanggan: " . mysqli_error($konek);
        exit;
    }

    // Dapatkan ID pelanggan yang baru saja disimpan
    $pelanggan_id = mysqli_insert_id($konek);

    // Simpan data penjualan ke tabel penjualan (kolom: TanggalPenjualan, TotalHarga, PelangganID)
    $insertPenjualan = "INSERT INTO penjualan (TanggalPenjualan, TotalHarga, PelangganID) 
                        VALUES (NOW(), '$totalHarga', '$pelanggan_id')";
    $resultPenjualan = mysqli_query($konek, $insertPenjualan);
    if (!$resultPenjualan) {
        echo "Gagal memproses penjualan: " . mysqli_error($konek);
        exit;
    }
    // Dapatkan ID penjualan yang baru saja dibuat
    $penjualan_id = mysqli_insert_id($konek);

    // Simpan detail penjualan ke tabel detailpenjualan
    $subtotal = $totalHarga;
    $insertDetailPenjualan = "INSERT INTO detailpenjualan (PenjualanID, ProdukID, JumlahProduk, Subtotal) 
                          VALUES ('$penjualan_id', '$produk_id', '$orderQty', '$subtotal')";
    $resultDetail = mysqli_query($konek, $insertDetailPenjualan);
    if (!$resultDetail) {
        echo "Gagal menyimpan detail penjualan: " . mysqli_error($konek);
        exit;
    }
    // Hitung stok baru dan update tabel produk
    $newStock = $stokAvailable - $orderQty;
    $updateQuery = "UPDATE produk SET Stok = '$newStock' WHERE ProdukID='$produk_id'";
    $updateResult = mysqli_query($konek, $updateQuery);

    if ($updateResult) {
        header('location:transaksi.php');
    } else {
        echo "Gagal memproses pesanan.";
    }
    exit;
} else {
    // TAMPILKAN FORM PEMESANAN (metode GET)
    if (isset($_GET['ProdukID'])) {
        $produk_id = $_GET['ProdukID'];
        $query = "SELECT * FROM produk WHERE ProdukID='$produk_id'";
        $result = mysqli_query($konek, $query);
        if (!$result || mysqli_num_rows($result) == 0) {
            echo "Produk tidak ditemukan.";
            exit;
        }
        $data = mysqli_fetch_assoc($result);
        if (isset($_GET['order_qty'])) {
            $orderQty = (int)$_GET['order_qty'];
            $totalHarga = $orderQty * $data['Harga'];
        }
    } else {
        echo "Produk tidak ditentukan.";
        exit;
    }
}
?>