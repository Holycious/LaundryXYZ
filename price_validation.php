<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

function calculatePrice($weight, $service, $speed, $isMember) {
    $prices = ['Cuci Kering' => 5000, 'Cuci Setrika' => 8000, 'Setrika' => 6000];
    $pricePerKilo = $prices[$service]; 
    $basePrice = $pricePerKilo * $weight; 
    $additionalFee = 0;
    if ($speed == "Ekspress") {
        $additionalFee = 2000 * $weight;
    }

    $totalPrice = $basePrice + $additionalFee; 

    $discount = 0;
    if ($isMember) {
        $discount = 0.1 * $totalPrice;
    }

    $finalPrice = $totalPrice - $discount;

    return [
        'basePrice' => $basePrice,
        'additionalFee' => $additionalFee,
        'total' => $totalPrice,
        'discount' => $discount,
        'finalPrice' => $finalPrice
    ];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $weight = intval($_POST['weight']);
    $service = $_POST['service'];
    $speed = $_POST['speed'];
    $isMember = isset($_POST['isMember']);
    $priceDetails = calculatePrice($weight, $service, $speed, $isMember);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Harga - Laundry XYZ</title>
    <link rel="stylesheet" href="price_validation.css">
</head>
<body>
<div class="navbar">
      <a href="home.html"
        ><img src="images/LAUNDRY.png" alt="Logo Laundry XYZ"
      /></a>
      <div class="nav-links">
        <a href="home.html">Home</a>
        <a href="price_validation.php">Cek Harga</a>
        <a href="logout.php">Logout</a>
      </div>
    </div>
    <br>
    <div class="wrapper">
        <h1>Cek Harga Laundry</h1>
        <br>
        <form method="POST">
            <label for="weight">Berat (Kg)</label>
            <input type="number" name="weight" placeholder="Berat (Kg)" required>

            <label for="service">Jenis Layanan</label>
            <select name="service" required>
                <option value="Cuci Kering">Cuci Kering</option>
                <option value="Cuci Setrika">Cuci Setrika</option>
                <option value="Setrika">Setrika</option>
            </select>

            <label for="speed">Kecepatan</label>
            <select name="speed" required>
                <option value="Reguler">Reguler</option>
                <option value="Ekspress">Ekspress</option>
            </select>

            <div class="checkbox-container">
            <label for="isMember">Member:</label>
                <input type="checkbox" name="isMember" style="margin-left: 5px;">
            </div>
            <button type="submit">CHECK</button>
        </form>
 

        <?php if (isset($priceDetails)): ?>
        <div class="receipt">
            <h2>Total Harga</h2>
            <table>
                <tr>
                    <th>Item</th>
                    <th>Harga</th>
                </tr>
                <tr>
                    <td><?= $service?> (<?= $weight ?> Kg x Rp <?= number_format($priceDetails['basePrice'] / $weight, 0, ',', '.') ?>)</td>
                    <td>Rp <?= number_format($priceDetails['basePrice'], 0, ',', '.') ?></td>
                </tr>
                <?php if ($priceDetails['additionalFee'] > 0): ?>
                <tr>
                    <td>Biaya Ekspress</td>
                    <td>Rp <?= number_format($priceDetails['additionalFee'], 0, ',', '.') ?></td>
                </tr>
                <?php endif; ?>
                <?php if ($priceDetails['discount'] > 0): ?>
                <tr class="discount">
                    <td>Diskon Member (10%)</td>
                    <td>Rp <?= number_format($priceDetails['discount'], 0, ',', '.') ?></td>
                </tr>
                <?php endif; ?>
                <tr class="final">
                    <td>Total Bayar</td>
                    <td>Rp <?= number_format($priceDetails['finalPrice'], 0, ',', '.') ?></td>
                </tr>
            </table>
            <div class="footer">
                Terima kasih telah menggunakan layanan kami!
            </div>

        </div>
        <?php endif; ?>
    </div>
    <footer>
      <p>&copy; 2024 Laundry XYZ. All Rights Reserved.</p>
    </footer>
</body>
</html>
