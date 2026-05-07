<?php
session_start();
include 'baglanti.php';


if (!isset($_SESSION['kullanici_id']) || $_SESSION['kullanici_id'] != 1) {
    header("Location: panel.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($baglanti, $_GET['id']);
    
    $sorgu = "UPDATE tarifler SET onay_durumu = 1 WHERE id = '$id'";
    
    if (mysqli_query($baglanti, $sorgu)) {
       
        header("Location: yonetim.php?onay=basarili");
    } else {
        echo "Hata oluştu: " . mysqli_error($baglanti);
    }
} else {
    header("Location: yonetim.php");
}
?>