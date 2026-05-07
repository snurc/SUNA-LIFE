<?php
$baglanti = mysqli_connect("localhost", "root", "", "suna_db");
mysqli_set_charset($baglanti, "utf8mb4");
if (!$baglanti) { die("Bağlantı Hatası: " . mysqli_connect_error()); }
?>