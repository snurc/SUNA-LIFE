<?php
include 'baglanti.php';
// Sadece botun eklediği 'http' ile başlayan linkleri siliyoruz, senin verilerin güvende!
mysqli_query($baglanti, "UPDATE tarifler SET tarif_resim = '' WHERE tarif_resim LIKE 'http%'");
echo "<h1>Temizlik Tamamlandı Şefim! Veritabanı pırıl pırıl.</h1>";
?>