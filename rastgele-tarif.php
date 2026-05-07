<?php
include 'baglanti.php';
$sorgu = mysqli_query($baglanti, "SELECT id FROM tarifler ORDER BY RAND() LIMIT 1");
$tarif = mysqli_fetch_assoc($sorgu);
header("Location: tarif-detay.php?id=" . $tarif['id']);
exit();
?>