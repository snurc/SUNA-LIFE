<?php
session_start();
include 'baglanti.php'; 


if (!isset($_SESSION['kullanici_id']) || $_SESSION['kullanici_id'] != 1) {
    header("Location: panel.php");
    exit();
}


if (isset($_GET['id'])) {
    $silinecek_id = mysqli_real_escape_string($baglanti, $_GET['id']);


    if ($silinecek_id == 1) {
        header("Location: yonetim.php?hata=kendini-silemezsin");
        exit();
    }


    $sorgu = "DELETE FROM kullanicilar WHERE id = '$silinecek_id'"; //[cite: 9, 10]
    
    if (mysqli_query($baglanti, $sorgu)) {
      
        header("Location: yonetim.php?durum=basarili");
    } else {
        header("Location: yonetim.php?durum=hata");
    }
} else {
    header("Location: yonetim.php");
}
?>