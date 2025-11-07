<?php
// Commit 3: Proses Session
session_start();

// Cek apakah user belum login, jika ya, arahkan kembali ke index.php
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("location: index.php");
    exit;
}

// Commit 5: Buat 5 produk menggunakan array
$products = [
    ['kode' => 'K001', 'nama' => 'Teh Pucuk', 'harga' => 5000],
    ['kode' => 'K002', 'nama' => 'Sukro', 'harga' => 1000],
    ['kode' => 'K003', 'nama' => 'Sprite', 'harga' => 4000],
    ['kode' => 'K004', 'nama' => 'Coca-Cola', 'harga' => 5000],
    ['kode' => 'K005', 'nama' => 'Chitose', 'harga' => 3000],
];

// Commit 6: Tambahkan array dan variabel
$pembelian = []; // Array untuk menyimpan detail pembelian acak
$grandtotal = 0;

// Commit 6: Gunakan perulangan for untuk memilih barang dan jumlah pembelian secara acak.
// Asumsi memilih 5 hingga 10 item acak
$jumlah_transaksi_acak = rand(5, 10); 
$product_keys = array_keys($products);

for ($i = 0; $i < $jumlah_transaksi_acak; $i++) {
    $random_product_key = $product_keys[array_rand($product_keys)]; // Pilih produk acak
    $barang = $products[$random_product_key];
    $jumlah_beli = rand(1, 5); // Jumlah pembelian acak antara 1 dan 5

    $pembelian[] = [
        'kode' => $barang['kode'],
        'nama' => $barang['nama'],
        'harga' => $barang['harga'],
        'jumlah' => $jumlah_beli,
    ];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POLGAN MART - Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .header { background: #007bff; color: white; padding: 10px 20px; border-radius: 4px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h3 { color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 5px; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total-row td { font-weight: bold; background-color: #e9ecef; }
        .logout-btn { padding: 8px 15px; background-color: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .welcome { font-size: 1.1em; font-weight: bold; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>--POLGAN MART--</h1> 
        <div>
            <span class="welcome">Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span> 
            <a href="logout.php" class="logout-btn">Logout</a> 
        </div>
    </div>

    <div class="container">
        <h3>Daftar Pembelian</h3>
        
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th class="text-right">Harga (Rp)</th>
                    <th class="text-right">Jumlah</th>
                    <th class="text-right">Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                // Commit 7: Gunakan foreach untuk menampilkan detail pembelian.
                foreach ($pembelian as $item) {
                    // Commit 7: Hitung total harga per item
                    $total_item = $item['harga'] * $item['jumlah'];
                    // Commit 7: akumulasikan ke variabel $grandtotal.
                    $grandtotal += $total_item;
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($item['kode']); ?></td>
                        <td><?php echo htmlspecialchars($item['nama']); ?></td>
                        <td class="text-right"><?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                        <td class="text-right"><?php echo htmlspecialchars($item['jumlah']); ?></td>
                        <td class="text-right"><?php echo number_format($total_item, 0, ',', '.'); ?></td>
                    </tr>
                    <?php
                }
                ?>
                
                <tr class="total-row">
                    <td colspan="5" class="text-right">Total Belanja</td>
                    <td class="text-right"><?php echo number_format($grandtotal, 0, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>