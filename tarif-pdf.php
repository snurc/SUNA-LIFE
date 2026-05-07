<?php
include 'baglanti.php';
$id = isset($_GET['id']) ? mysqli_real_escape_string($baglanti, $_GET['id']) : 0;
$sorgu = mysqli_query($baglanti, "SELECT t.*, k.kullanici_adi FROM tarifler t JOIN kullanicilar k ON t.ekleyen_id = k.id WHERE t.id = '$id'");
$tarif = mysqli_fetch_assoc($sorgu);
if (!$tarif) { echo "Hata!"; exit(); }
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tarif['baslik']) ?> - Suna's Life PDF</title>
    <style>

        :root {
            --bordo: #800000;
            --altin: #D4AF37;
            --krem-arka: #FFFBF2;
            --yazi: #2D1B1B;
            --gri-soft: #888;
        }

        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            padding: 40px; 
            line-height: 1.6; 
            background-color: #fff; 
            color: var(--yazi);
        }

  
        .header { 
            border-bottom: 5px solid var(--bordo); 
            margin-bottom: 30px; 
            padding-bottom: 15px; 
            position: relative;
        }

        .brand-name {
            color: var(--altin);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .title { 
            color: var(--bordo); 
            font-size: 32px; 
            margin: 0; 
            font-weight: 800;
        }

        .meta { 
            color: var(--gri-soft); 
            font-style: italic; 
            font-size: 14px;
            margin-top: 5px;
        }

  
        .section-title { 
            color: var(--bordo); 
            border-left: 5px solid var(--altin); 
            padding-left: 15px;
            margin-top: 30px; 
            margin-bottom: 15px;
            font-size: 20px; 
            text-transform: uppercase;
            font-weight: bold;
        }

   
        .content { 
            background: var(--krem-arka); 
            padding: 20px; 
            border-radius: 15px; 
            border: 1px solid #F5E6D3;
            font-size: 15px;
        }

        .footer {
            margin-top: 50px; 
            text-align: center; 
            font-size: 11px; 
            color: var(--gri-soft); 
            border-top: 1px solid #eee; 
            padding-top: 15px;
            letter-spacing: 1px;
        }

        @media print { 
            .no-print { display: none; } 
            body { padding: 20px; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <span class="brand-name">Suna's Life Akıllı Mutfak</span>
        <h1 class="title"><?= htmlspecialchars($tarif['baslik']) ?></h1>
        <div class="meta">
            <b>Şef:</b> <?= htmlspecialchars($tarif['kullanici_adi']) ?> &nbsp; | &nbsp; 
            <b>Kategori:</b> <?= htmlspecialchars($tarif['kategori']) ?> &nbsp; | &nbsp;
            <b>Tarih:</b> <?= date('d.m.Y') ?>
        </div>
    </div>

    <div class="section-title">Gerekli Malzemeler</div>
    <div class="content">
        <?= nl2br(htmlspecialchars($tarif['malzemeler'])) ?>
    </div>

    <div class="section-title">Hazırlanış Aşamaları</div>
    <div class="content">
        <?= nl2br(htmlspecialchars($tarif['yapilis'])) ?>
    </div>

    <div class="footer">
        Bu belge <b>Suna's Life Akıllı Mutfak Sistemi</b> tarafından otomatik oluşturulmuştur.<br>
        <i>Lezzet ve Sanatla Kalın...</i>
    </div>

</body>
</html>