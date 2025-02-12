<?php
include "proses_pemesanan.php"
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Produk</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="form-container">
        <h1>Pesan Produk</h1>
        <!-- Tampilan data produk (read-only) -->
        <label>Produk</label>
        <input type="text" name="NamaProduk" value="<?php echo htmlspecialchars($data['NamaProduk']); ?>" readonly>

        <label>Harga</label>
        <input type="text" name="Harga" value="Rp <?php echo number_format($data['Harga'], 2, ',', '.'); ?>" readonly>

        <label>Stok Tersedia</label>
        <input type="text" name="Stok" value="<?php echo htmlspecialchars($data['Stok']); ?>" readonly>

        <!-- Form pemesanan -->
        <form action="pesanan.php?ProdukID=<?php echo $data['ProdukID']; ?>" method="post">
            <label for="NamaPelanggan">Nama Pelanggan</label>
            <input type="text" id="NamaPelanggan" name="NamaPelanggan" placeholder="Nama Pelanggan" required>

            <label for="Alamat">Alamat</label>
            <input type="text" id="Alamat" name="Alamat" placeholder="Alamat" required>

            <label for="NomorTelepon">Nomor Telepon</label>
            <input type="tel" id="NomorTelepon" name="NomorTelepon" placeholder="Nomor Telepon" pattern="[0-9]{12}" required>

            <input type="hidden" name="ProdukID" value="<?php echo $data['ProdukID']; ?>">

            <label for="order_qty">Jumlah Pesanan:</label>
            <input type="number" id="order_qty" name="order_qty" min="1" max="<?php echo htmlspecialchars($data['Stok']); ?>" required>

            <label for="total_harga">Total Harga:</label>
            <input type="text" id="total_harga" name="total_harga" value="Rp 0,00" readonly>

            <input class="btn btn-success" type="submit" value="Pesan Produk">
        </form>
    </div>
</body>

</html>
<script>
    document.getElementById('order_qty').addEventListener('input', function() {
        var harga = <?php echo $data['Harga']; ?>;
        var jumlahPesanan = parseInt(this.value) || 0;
        var totalHarga = harga * jumlahPesanan;

        document.getElementById('total_harga').value = 'Rp ' + totalHarga.toLocaleString('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    });
</script>