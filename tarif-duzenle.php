<?php
session_start();
include 'baglanti.php';


if (!isset($_SESSION['kullanici_id'])) { header("Location: giris.php"); exit(); }

$tarif_id = isset($_GET['id']) ? mysqli_real_escape_string($baglanti, $_GET['id']) : 0;

if (isset($_POST['guncelle'])) {
    $baslik = mysqli_real_escape_string($baglanti, $_POST['baslik']);
    $malzemeler = mysqli_real_escape_string($baglanti, $_POST['malzemeler']);
    $yapilis = mysqli_real_escape_string($baglanti, $_POST['yapilis']);
    $kalori = (int)$_POST['kalori'];
    $diyet = mysqli_real_escape_string($baglanti, $_POST['diyet_tipi']);

    $guncelle_sorgu = "UPDATE tarifler SET baslik='$baslik', malzemeler='$malzemeler', yapilis='$yapilis', kalori='$kalori', diyet_tipi='$diyet' WHERE id='$tarif_id'";
    
    if (mysqli_query($baglanti, $guncelle_sorgu)) {
        header("Location: panel.php?durum=ok"); 
        exit();
    } else {
        echo "Hata: " . mysqli_error($baglanti);
    }
}

$sorgu = mysqli_query($baglanti, "SELECT * FROM tarifler WHERE id = '$tarif_id'");
$tarif = mysqli_fetch_assoc($sorgu);
if (!$tarif) { echo "Tarif bulunamadı!"; exit(); }
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarif Düzenle | Suna's Life</title>
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

        body { background-color: var(--arkaplan); font-family: 'Poppins', sans-serif; color: var(--yazi); }
        
        .edit-card { 
            background: white;
            border-radius: 40px; 
            border: 1px solid var(--krem-koyu); 
            box-shadow: 0 20px 60px rgba(128, 0, 0, 0.05); 
            margin-top: 50px; 
            padding: 50px;
            position: relative;
            overflow: hidden;
        }

        .edit-card::before {
            content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 8px;
            background: linear-gradient(90deg, var(--bordo), var(--altin));
        }

        .header-title { font-family: 'Playfair Display', serif; color: var(--bordo); font-weight: 800; margin-bottom: 30px; }
        .header-title span { color: var(--altin); }

        .form-label { color: var(--bordo); font-weight: 700; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        
        .form-control {
            background: var(--arkaplan) !important;
            border: 2px solid var(--krem-koyu) !important;
            color: var(--yazi) !important;
            border-radius: 15px;
            padding: 14px 20px;
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: var(--bordo) !important;
            box-shadow: 0 0 0 5px rgba(128, 0, 0, 0.05) !important;
            background: white !important;
        }

        .btn-update {
            background: linear-gradient(135deg, var(--bordo) 0%, var(--bordo-acik) 100%);
            color: white;
            border: none;
            border-radius: 20px;
            padding: 15px 40px;
            font-weight: 700;
            transition: 0.4s;
            box-shadow: 0 10px 25px rgba(128, 0, 0, 0.2);
            letter-spacing: 1px;
        }

        .btn-update:hover {
            background: var(--altin);
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(212, 175, 55, 0.3);
            color: white;
        }

        .btn-cancel {
            background: var(--krem-koyu);
            color: var(--bordo);
            border: none;
            border-radius: 20px;
            padding: 15px 30px;
            font-weight: 700;
            transition: 0.3s;
        }
        
        .btn-cancel:hover { background: #e2d1bc; color: var(--bordo); }

        .icon-circle {
            width: 60px; height: 60px; background: var(--krem-koyu); color: var(--bordo);
            border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.5rem; margin-bottom: 20px; border: 1px solid var(--altin);
        }
    </style>
</head>
<body>
    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card edit-card">
                    <div class="text-center">
                        <div class="icon-circle">
                            <i class="fas fa-edit"></i>
                        </div>
                        <h2 class="header-title">Tarifi <span>Güncelle</span></h2>
                    </div>

                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label">Lezzet Başlığı</label>
                            <input type="text" name="baslik" class="form-control" value="<?= htmlspecialchars($tarif['baslik']) ?>" required placeholder="Tarifinizin adı...">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Gerekli Malzemeler</label>
                            <textarea name="malzemeler" class="form-control" rows="4" placeholder="Malzemeleri virgülle ayırarak yazın..."><?= htmlspecialchars($tarif['malzemeler']) ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Hazırlanış Aşamaları</label>
                            <textarea name="yapilis" class="form-control" rows="6" placeholder="Adım adım yapılışını anlatın..."><?= htmlspecialchars($tarif['yapilis']) ?></textarea>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Enerji Değeri (kcal)</label>
                                <input type="number" name="kalori" class="form-control" value="<?= $tarif['kalori'] ?>" placeholder="Örn: 250">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Diyet / Kategori Tipi</label>
                                <input type="text" name="diyet_tipi" class="form-control" value="<?= htmlspecialchars($tarif['diyet_tipi']) ?>" placeholder="Örn: Vejetaryen, Glutensiz">
                            </div>
                        </div>

                        <div class="text-center mt-5 d-flex justify-content-center gap-3">
                            <a href="panel.php" class="btn btn-cancel">
                                <i class="fas fa-times me-2"></i>VAZGEÇ
                            </a>
                            <button type="submit" name="guncelle" class="btn btn-update">
                                DEĞİŞİKLİKLERİ KAYDET <i class="fas fa-check-circle ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>