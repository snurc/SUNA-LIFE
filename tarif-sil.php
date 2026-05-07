<?php
session_start();
include 'baglanti.php';


if (!isset($_SESSION['kullanici_id']) || $_SESSION['kullanici_id'] != 1) {
    header("Location: giris.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Silme sorgusu
    $sil = mysqli_query($baglanti, "DELETE FROM tarifler WHERE id = $id");

    if ($sil) {
        $durum = "ok";
    } else {
        $durum = "hata";
    }
} else {
    header("Location: panel.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Suna's Life | İşlem Yapılıyor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { 
            --bordo: #800000; 
            --altin: #D4AF37;
            --krem: #FFFBF2;
        }
        body { 
            background: var(--krem); 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-family: 'Poppins', sans-serif;
        }
        .loader-card {
            text-align: center;
            background: white;
            padding: 50px;
            border-radius: 40px;
            box-shadow: 0 20px 50px rgba(128, 0, 0, 0.05);
            border: 1px solid rgba(128, 0, 0, 0.1);
        }
        .spinner-bordo {
            width: 3rem; height: 3rem;
            color: var(--bordo);
        }
    </style>
    <meta http-equiv="refresh" content="1.5;url=panel.php?durum=<?= $durum == 'ok' ? 'silindi' : 'hata' ?>">
</head>
<body>

    <div class="loader-card">
        <div class="spinner-border spinner-bordo mb-4" role="status"></div>
        <h4 class="fw-bold" style="color: var(--bordo);">
            <?= $durum == "ok" ? "Tarif Arşivden Kaldırılıyor..." : "Bir Hata Oluştu!" ?>
        </h4>
        <p class="text-muted small mb-0">Lütfen bekleyin, mutfak düzenleniyor.</p>
    </div>

</body>
</html>