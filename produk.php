<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Data Produk</title>
  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <nav>
    <h1>Dashboard Pelanggan</h1>
    <ul>
      <form method="GET" action="produk.php" class="search-form">
        <input type="text" name="search" placeholder="Cari produk..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
      </form>
      <li><a href="index.php">Halaman Utama</a></li>
      <li><a href="produk.php">Produk</a></li>
      <li><a href="transaksi.php">Transaksi</a></li>
    </ul>
    <!-- Form Pencarian di dalam nav -->

  </nav>

  <div>
    <ul class="produk-list">
      <?php
      include "koneksi.php";

      // Cek apakah ada input pencarian
      if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
        $search = mysqli_real_escape_string($konek, trim($_GET['search']));

        // Mencoba mengidentifikasi apakah input adalah angka (untuk pencarian harga atau stok)
        if (is_numeric($search)) {
          $query = "SELECT * FROM produk WHERE NamaProduk LIKE '%$search%' OR Harga = $search OR Stok = $search";
        } else {
          $query = "SELECT * FROM produk WHERE NamaProduk LIKE '%$search%'";
        }
      } else {
        $query = "SELECT * FROM produk";
      }

      $result = mysqli_query($konek, $query);

      if (mysqli_num_rows($result) > 0) {
        while ($data = mysqli_fetch_array($result)) {
      ?>
          <li class="produk-item">
            <div class="produk-header text-center">
              <?php echo htmlspecialchars($data['NamaProduk']); ?>
            </div>
            <div class="produk-detail">
              <span class="produk-harga">
                Harga: Rp <?php echo number_format($data['Harga'], 2, ',', '.'); ?>
              </span>
              <span class="produk-stok">
                Stok: <?php echo htmlspecialchars($data['Stok']); ?>
              </span>
            </div>
            <div class="produk-action text-center">
              <!-- Tombol Pesan/Checkout -->
              <a href="pesanan.php?ProdukID=<?php echo $data['ProdukID']; ?>" class="btn btn-succes">Pesan</a>
            </div>
          </li>
      <?php
        }
      } else {
        echo "<li style='text-align: center;'>Data Produk Belum Tersedia</li>";
      }
      ?>
    </ul>
  </div>
</body>

</html>