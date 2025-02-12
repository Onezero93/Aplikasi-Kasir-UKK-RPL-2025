<?php
require('fpdf/fpdf.php'); // Pastikan file FPDF ada di folder yang benar
include "koneksi.php"; // Pastikan koneksi database sudah benar

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(190, 10, 'SMK RAUDDATUL JANNAH', 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Cell(190, 5, 'Jl. Contoh No.123, Kota Anda', 0, 1, 'C');
        $this->Cell(190, 5, 'Telp: (021) 1234 5678', 0, 1, 'C');
        $this->Ln(5);
    }
    
    function Footer() {
        $this->SetY(-150);
        $this->SetFont('Arial', 'I', 10);
        $this->Cell(190, 10, 'Terima Kasih Telah Berbelanja di SMK RAUDDATUL JANNAH', 0, 1, 'C');
        $this->Cell(190, 5, 'Simpan struk ini sebagai bukti pembayaran.', 0, 1, 'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(190, 5, 'Tanggal: ' . date('d-m-Y H:i:s'), 0, 1, 'C');
$pdf->Ln(5);

// Ambil satu data terbaru dari database
$query = "SELECT p.NamaProduk, p.Harga, dp.JumlahProduk, dp.Subtotal, 
                 pj.TanggalPenjualan, pl.NamaPelanggan 
          FROM detailpenjualan dp
          JOIN penjualan pj ON dp.PenjualanID = pj.PenjualanID
          JOIN produk p ON dp.ProdukID = p.ProdukID
          JOIN pelanggan pl ON pj.PelangganID = pl.PelangganID
          ORDER BY dp.DetailID DESC LIMIT 1";
$result = mysqli_query($konek, $query);

if ($row = mysqli_fetch_assoc($result)) {
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(190, 8, 'Detail Transaksi', 0, 1, 'C');
    $pdf->Ln(5);
    
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(50, 8, 'Nama Pelanggan', 0, 0, 'L');
    $pdf->Cell(140, 8, ': ' . $row['NamaPelanggan'], 0, 1, 'L');
    
    $pdf->Cell(50, 8, 'Nama Produk', 0, 0, 'L');
    $pdf->Cell(140, 8, ': ' . $row['NamaProduk'], 0, 1, 'L');
    
    $pdf->Cell(50, 8, 'Harga Satuan', 0, 0, 'L');
    $pdf->Cell(140, 8, ': Rp ' . number_format($row['Harga'], 2, ',', '.'), 0, 1, 'L');
    
    $pdf->Cell(50, 8, 'Jumlah', 0, 0, 'L');
    $pdf->Cell(140, 8, ': ' . $row['JumlahProduk'], 0, 1, 'L');
    
    $pdf->Cell(50, 8, 'Subtotal', 0, 0, 'L');
    $pdf->Cell(140, 8, ': Rp ' . number_format($row['Subtotal'], 2, ',', '.'), 0, 1, 'L');
}

$pdf->Output(); // Tampilkan PDF
?>
