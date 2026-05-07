<?php
session_start();
include 'baglanti.php';


if (!isset($_SESSION['kullanici_id']) || $_SESSION['kullanici_id'] != 1) {
    header("Location: panel.php");
    exit();
}

$mesaj = "";

if (isset($_POST['aktar'])) {
    $dosya = $_FILES['csv_dosya']['tmp_name'];
    
    if (($handle = fopen($dosya, "r")) !== FALSE) {
        // İlk satırı (başlıkları) atla
        fgetcsv($handle, 1000, ",");
        
        $sayac = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $baslik = mysqli_real_escape_string($baglanti, $data[0]);
            $kategori = mysqli_real_escape_string($baglanti, $data[1]);
            $video_url = mysqli_real_escape_string($baglanti, $data[2]);
            $ekleyen_id = 1; // Admin ekliyor

            $sorgu = "INSERT INTO tarifler (baslik, kategori, video_url, ekleyen_id) 
                      VALUES ('$baslik', '$kategori', '$video_url', '$ekleyen_id')";
            mysqli_query($baglanti, $sorgu);
            $sayac++;
        }
        fclose($handle);
        $mesaj = "<div class='alert alert-bordo shadow-sm rounded-4 mb-4'><i class='fas fa-check-circle me-2'></i> ✨ $sayac adet tarif mutfağa toplu olarak eklendi şefim!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toplu Tarif Aktar | Suna's Life</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { 
            --bordo: #800000; 
            --bordo-acik: #A52A2A;
            --altin: #D4AF37;
            --arkaplan: #FFFBF2;
            --krem-koyu: #F5E6D3;
            --yazi: #2D1B1B;
        }

        body { background-color: var(--arkaplan); font-family: 'Poppins', sans-serif; color: var(--yazi); margin: 0; display: flex; }
        .main-content { flex-grow: 1; padding: 40px; margin-left: 280px; min-height: 100vh; }

        .header-title { font-family: 'Playfair Display', serif; color: var(--bordo); font-weight: 800; font-size: 2.2rem; margin-bottom: 35px; }
        .header-title span { color: var(--altin); }

        /* YÜKLEME KARTI */
        .import-card { 
            background: white; 
            border-radius: 40px; 
            padding: 60px 40px; 
            border: 2px dashed var(--krem-koyu); 
            text-align: center; 
            box-shadow: 0 20px 50px rgba(128, 0, 0, 0.03);
            transition: 0.3s;
            max-width: 700px;
            margin: 0 auto;
        }
        .import-card:hover { border-color: var(--bordo); background: #ffffff; }

        .icon-box-large {
            width: 100px; height: 100px;
            background: var(--krem-koyu);
            color: var(--bordo);
            border-radius: 30px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 3rem; margin-bottom: 30px;
            border: 2px solid var(--altin);
            transform: rotate(-5deg);
            box-shadow: 0 10px 25px rgba(128, 0, 0, 0.1);
        }

  
        .form-control {
            background: var(--arkaplan) !important;
            border: 2px solid var(--krem-koyu) !important;
            color: var(--yazi) !important;
            border-radius: 15px;
            padding: 15px 25px;
            transition: 0.3s;
        }
        .form-control:focus {
            border-color: var(--bordo) !important;
            box-shadow: 0 0 0 5px rgba(128, 0, 0, 0.05) !important;
            background: white !important;
        }

        .btn-import {
            background: linear-gradient(135deg, var(--bordo) 0%, var(--bordo-acik) 100%);
            color: white;
            border: none;
            border-radius: 20px;
            padding: 18px 50px;
            font-weight: 700;
            letter-spacing: 1px;
            transition: 0.4s;
            box-shadow: 0 15px 30px rgba(128, 0, 0, 0.2);
            width: 100%;
            margin-top: 20px;
        }
        .btn-import:hover {
            background: var(--altin);
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(212, 175, 55, 0.3);
            color: white;
        }

        .alert-bordo {
            background: var(--krem-koyu);
            color: var(--bordo);
            border: 1px solid var(--bordo);
            font-weight: 600;
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <h2 class="header-title">Toplu Tarif <span>Yükleme</span></h2>
        
        <div class="import-card shadow-sm">
            <?= $mesaj ?>

            <div class="icon-box-large">
                <i class="fas fa-file-csv"></i>
            </div>
            
            <h4 class="fw-bold mb-3" style="color: var(--bordo);">CSV Dosyası Yükleyin</h4>
            <p class="text-muted mb-5 px-lg-5">Mutfak arşivinizi büyütmek için Excel veya CSV formatındaki dosyalarınızı buraya bırakın. Tek hamlede yüzlerce yeni lezzet ekleyin.</p>
            
            <form method="POST" enctype="multipart/form-data" class="text-start">
                <div class="mb-4 text-center">
                    <label class="form-label fw-bold small text-uppercase mb-3" style="letter-spacing: 1px; color: var(--bordo);">Dosya Seçin</label>
                    <input type="file" name="csv_dosya" class="form-control" accept=".csv" required>
                </div>
                
                <button type="submit" name="aktar" class="btn btn-import">
                    <i class="fas fa-upload me-2"></i> ŞİMDİ AKTARMAYI BAŞLAT
                </button>
            </form>

            <div class="mt-5 text-muted small">
                <i class="fas fa-info-circle me-1"></i> Dosyanızın; <b>Başlık, Kategori, Video URL</b> sütunlarını içerdiğinden emin olun.
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>