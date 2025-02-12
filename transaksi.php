<?php
include "koneksi.php";
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav>
        <h1>Dashboard Pelanggan</h1>
        <ul>
        <form method="GET" action="transaksi.php" class="search-form">
            <input type="text" name="search" placeholder="Cari Transaksi..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        </form>
            <li><a href="index.php">Halaman Utama</a></li>
            <li><a href="produk.php">Produk</a></li>
            <li><a href="transaksi.php">Transaksi</a></li>
        </ul>
    </nav>

    <div class="container">
        <ul class="produk-list">
            <?php
            // Ambil input pencarian jika ada
            $search = isset($_GET['search']) ? mysqli_real_escape_string($konek, $_GET['search']) : '';

            // Query untuk mengambil data transaksi
            $query = "SELECT dp.DetailID, dp.PenjualanID, p.NamaProduk, dp.JumlahProduk, dp.Subtotal, 
                             pj.TanggalPenjualan, pl.NamaPelanggan 
                      FROM detailpenjualan dp
                      JOIN penjualan pj ON dp.PenjualanID = pj.PenjualanID
                      JOIN produk p ON dp.ProdukID = p.ProdukID
                      JOIN pelanggan pl ON pj.PelangganID = pl.PelangganID";

            // Jika ada pencarian, tambahkan filter ke query
            if (!empty($search)) {
                $query .= " WHERE pl.NamaPelanggan LIKE '%$search%'
                            OR p.NamaProduk LIKE '%$search%'
                            OR dp.JumlahProduk LIKE '%$search%'
                            OR dp.Subtotal LIKE '%$search%'
                            OR pj.TanggalPenjualan LIKE '%$search%'";
            }

            $query .= " ORDER BY dp.DetailID DESC";
            $result = mysqli_query($konek, $query);

            // Tampilkan hasil pencarian
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                    <li class="produk-item">
                        <div class="produk-header text-center">
                            <strong>Penjualan ID: <?php echo htmlspecialchars($row['PenjualanID']); ?></strong>
                        </div>
                        <div class="produk-detail">
                            <span class="produk-harga">
                                Nama Pelanggan: <?php echo htmlspecialchars($row['NamaPelanggan']); ?>
                            </span>
                            <span class="produk-stok">
                                Produk: <?php echo htmlspecialchars($row['NamaProduk']); ?>
                            </span>
                            <span class="produk-stok">
                                Jumlah: <?php echo htmlspecialchars($row['JumlahProduk']); ?>
                            </span>
                            <span class="produk-stok">
                                Subtotal: Rp <?php echo number_format($row['Subtotal'], 2, ',', '.'); ?>
                            </span>
                            <span class="produk-stok">
                                Tanggal: <?php echo htmlspecialchars($row['TanggalPenjualan']); ?>
                            </span>
                        </div>
                        <div class="produk-action text-center">
                            <a href="cetak_transaksi.php?id=<?php echo $row['PenjualanID']; ?>" class="btn btn-succes" target="_blank">Cetak Struk</a>
                        </div>
                    </li>
            <?php
                }
            } else {
                echo "<li style='text-align: center;'>Tidak ada transaksi yang cocok dengan pencarian.</li>";
            }
            ?>
        </ul>
    </div>
</body>

</html>
