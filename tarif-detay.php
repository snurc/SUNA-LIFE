<?php
session_start();
include 'baglanti.php';

if (!isset($_SESSION['kullanici_id'])) {
    header("Location: giris.php");
    exit();
}

$kullanici_id = $_SESSION['kullanici_id'];
$tarif_id = isset($_GET['id']) ? mysqli_real_escape_string($baglanti, $_GET['id']) : 0;


if (isset($_POST['begeni_yap'])) {
    $kontrol = mysqli_query($baglanti, "SELECT * FROM begeniler WHERE tarif_id = '$tarif_id' AND kullanici_id = '$kullanici_id'");
    if (mysqli_num_rows($kontrol) == 0) {
        mysqli_query($baglanti, "INSERT INTO begeniler (tarif_id, kullanici_id) VALUES ('$tarif_id', '$kullanici_id')");
    } else {
        mysqli_query($baglanti, "DELETE FROM begeniler WHERE tarif_id = '$tarif_id' AND kullanici_id = '$kullanici_id'");
    }
}

if (isset($_POST['yorum_yap'])) {
    $yorum_icerik = mysqli_real_escape_string($baglanti, $_POST['yorum_icerik']);
    if (!empty($yorum_icerik)) {
        mysqli_query($baglanti, "INSERT INTO yorumlar (tarif_id, kullanici_id, yorum_icerik) VALUES ('$tarif_id', '$kullanici_id', '$yorum_icerik')");
    }
}

// VERİLERİ ÇEK
$sorgu = mysqli_query($baglanti, "SELECT t.*, k.kullanici_adi FROM tarifler t JOIN kullanicilar k ON t.ekleyen_id = k.id WHERE t.id = '$tarif_id'");
$tarif = mysqli_fetch_assoc($sorgu);

if (!$tarif) { echo "Tarif bulunamadı!"; exit(); }

$toplam_begeni_sorgu = mysqli_query($baglanti, "SELECT COUNT(*) as toplam FROM begeniler WHERE tarif_id = '$tarif_id'");
$toplam_begeni = mysqli_fetch_assoc($toplam_begeni_sorgu)['toplam'];
$begendi_mi = mysqli_query($baglanti, "SELECT * FROM begeniler WHERE tarif_id = '$tarif_id' AND kullanici_id = '$kullanici_id'");
$kalp_rengi = (mysqli_num_rows($begendi_mi) > 0) ? "text-white" : "text-white-50";
$yorum_sorgu = mysqli_query($baglanti, "SELECT y.*, k.kullanici_adi FROM yorumlar y JOIN kullanicilar k ON y.kullanici_id = k.id WHERE y.tarif_id = '$tarif_id' ORDER BY y.tarih DESC");
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tarif['baslik']) ?> | Suna's Life</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { 
            --bordo: #800000; 
            --bordo-acik: #A52A2A;
            --altin: #D4AF37;
            --arkaplan: #FFFBF2;
            --krem-koyu: #F5E6D3;
            --yazi: #2D1B1B;
        }

        body { background: var(--arkaplan); font-family: 'Poppins', sans-serif; transition: 0.3s; color: var(--yazi); }
        .main-content { margin-left: 280px; padding: 40px; }
        

        .header-banner {
            background: linear-gradient(135deg, var(--bordo) 0%, var(--bordo-acik) 100%); 
            border-radius: 40px; padding: 60px 20px;
            text-align: center; box-shadow: 0 15px 35px rgba(128, 0, 0, 0.2); position: relative;
        }

        .category-badge { background: var(--altin); color: var(--bordo); font-weight: 700; padding: 8px 20px; border-radius: 12px; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; }
        
        .portion-box { background: rgba(255,255,255,0.1); border-radius: 50px; padding: 5px 15px; display: inline-flex; align-items: center; border: 1px solid rgba(255,255,255,0.2); margin-top: 20px; }
        .portion-btn { background: none; border: none; color: white; font-size: 1.2rem; padding: 0 10px; transition: 0.3s; cursor: pointer; }
        .portion-btn:hover { color: var(--altin); transform: scale(1.2); }

        .ingredient-list { background: #fff; border-radius: 30px; padding: 35px; border: 1px solid var(--krem-koyu); box-shadow: 0 10px 30px rgba(128,0,0,0.02); height: 100%; }
        .check-item { cursor: pointer; transition: 0.3s; display: flex; align-items: center; padding: 10px 0; border-bottom: 1px dashed var(--krem-koyu); }
        .check-item.done { text-decoration: line-through; opacity: 0.5; color: var(--altin); }
        .check-item input { pointer-events: none; }

        .info-card { background: #ffffff; border-radius: 35px; padding: 30px; border: 1px solid var(--krem-koyu); position: relative; overflow: hidden; margin-top: 30px; }
        .info-card::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 5px; background: linear-gradient(90deg, var(--altin), var(--bordo)); }
        
        .steps-container { background: #fff; border-radius: 30px; padding: 35px; border: 1px solid var(--krem-koyu); box-shadow: 0 10px 30px rgba(128,0,0,0.02); }
        .step-box { position: relative; padding-left: 80px; margin-bottom: 40px; transition: 0.3s; padding-top: 5px; }
        

        .step-number { 
            position: absolute; left: 0; top: 0; width: 55px; height: 55px; 
            background: var(--bordo); color: white; border-radius: 18px; 
            display: flex; align-items: center; justify-content: center; 
            font-weight: 800; font-size: 1.4rem; box-shadow: 0 8px 15px rgba(128, 0, 0, 0.2); 
        }
        
        .btn-heart { 
            width: 65px; height: 65px; border-radius: 50%; border: none; 
            background: var(--bordo); color: white; box-shadow: 0 10px 20px rgba(128,0,0,0.2); 
            transition: 0.3s; position: absolute; bottom: -32px; right: 50px; 
        }
        .btn-heart:hover { transform: scale(1.1); background: var(--altin); }
        
        .yorum-card { background: white; border-radius: 20px; border-left: 5px solid var(--bordo); transition: 0.3s; border-top: 1px solid var(--krem-koyu); border-right: 1px solid var(--krem-koyu); border-bottom: 1px solid var(--krem-koyu); }

        .qr-section { background: var(--arkaplan) !important; border: 2px dashed var(--altin) !important; color: var(--bordo) !important; }

        @media print {
            .sidebar-container, .portion-box, .btn-heart, .qr-section, form, .btn-print, .portion-btn { display: none !important; }
            .main-content { margin: 0 !important; padding: 0 !important; width: 100% !important; }
            .header-banner { background: white !important; border: 2px solid var(--bordo); color: black !important; padding: 20px !important; }
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <div class="header-banner mb-5">
            <span class="category-badge mb-3 d-inline-block"><?= htmlspecialchars($tarif['kategori']) ?></span>
            <h1 class="text-white display-4 fw-bold mb-3"><?= htmlspecialchars($tarif['baslik']) ?></h1>
            
            <div class="d-flex justify-content-center gap-4 text-white-50 mb-3">
                <span><i class="fas fa-user-circle me-2 text-warning"></i> Şef: <b><?= htmlspecialchars($tarif['kullanici_adi']) ?></b></span>
                <span onclick="startTimer(20)" style="cursor: pointer;" class="text-white">
                    <i class="far fa-clock me-2 text-warning"></i> <b id="timer-display">15-20 Dakika</b>
                </span>
                <span><i class="fas fa-heart me-2 text-warning"></i> <?= $toplam_begeni ?> Beğeni</span>
            </div>

            <div class="portion-box text-white">
                <span class="small me-2 opacity-75 text-uppercase fw-bold">Porsiyon:</span>
                <button class="portion-btn" onclick="adjustPortion(0.5)"><i class="fas fa-minus-circle"></i></button>
                <span id="multiplier-label" class="fw-bold fs-5 mx-2">1x</span>
                <button class="portion-btn" onclick="adjustPortion(2)"><i class="fas fa-plus-circle"></i></button>
            </div>

            <form method="POST">
                <button type="submit" name="begeni_yap" class="btn-heart">
                    <i class="fas fa-heart fa-lg <?= $kalp_rengi ?>"></i>
                </button>
            </form>
        </div>

        <div class="row g-5">
            <div class="col-lg-4">
                <div class="ingredient-list shadow-sm">
                    <h4 class="fw-bold mb-4" style="color:var(--bordo)">
                        <i class="fas fa-shopping-basket me-2 text-warning"></i> Malzemeler
                    </h4>
                    <div id="ingredients-container">
                        <?php 
                        $malzemeler = explode(",", $tarif['malzemeler']);
                        foreach($malzemeler as $m): if(trim($m) != ""): ?>
                            <div class="check-item" onclick="this.classList.toggle('done'); let c = this.querySelector('input'); c.checked = !c.checked;">
                                <input type="checkbox" class="form-check-input me-3 shadow-none">
                                <span class="ing-text" style="color: var(--yazi); opacity: 0.8;"><?= htmlspecialchars(trim($m)) ?></span>
                            </div>
                        <?php endif; endforeach; ?>
                    </div>
                </div>

                <div class="info-card shadow-sm">
                    <div class="qr-section p-3 rounded-4">
                        <p class="small fw-bold text-center mb-3">
                            <i class="fas fa-file-pdf me-2"></i>TARİFİ TELEFONA İNDİR (PDF)
                        </p>
                        <div class="d-flex justify-content-center">
                            <?php 
                                $pdf_link = "http://$_SERVER[HTTP_HOST]/suna/tarif-pdf.php?id=" . $tarif_id;
                                $qr_url = "https://quickchart.io/qr?text=" . urlencode($pdf_link) . "&size=150&light=fdfbf7&dark=800000";
                            ?>
                            <div class="bg-white p-2 rounded-3 shadow-sm border">
                                <img src="<?= $qr_url ?>" alt="QR PDF" class="img-fluid" style="width: 120px; height: 120px;">
                            </div>
                        </div>
                        <p class="text-center mt-2 mb-0" style="font-size: 0.65rem; font-weight:bold; color: var(--bordo);">PDF OLARAK KAYDETMEK İÇİN OKUT</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="steps-container shadow-sm">
                    <h4 class="fw-bold mb-5" style="color:var(--bordo)">
                        <i class="fas fa-fire-alt me-2 text-danger"></i> Hazırlanış Aşamaları
                    </h4>
                    <?php 
                    $adimlar = explode("\n", trim($tarif['yapilis']));
                    $i = 1;
                    foreach ($adimlar as $adim): if (trim($adim) != ""):
                    ?>
                        <div class="step-box">
                            <div class="step-number"><?= $i++ ?></div>
                            <h5 class="fw-bold mb-2" style="color:var(--bordo-acik)">ADIM</h5>
                            <p style="line-height: 1.8; font-size: 1.1rem; color: var(--yazi); opacity: 0.9;">
                                <?= htmlspecialchars(trim($adim)) ?>
                            </p>
                        </div>
                    <?php endif; endforeach; ?>
                </div>
            </div>
        </div>
    </main>

    <script>

    </script>
</body>
</html>