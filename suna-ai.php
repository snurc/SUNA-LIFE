<?php
session_start();
include 'baglanti.php';

$onerilen_tarifler = null;
$malzemeler_input = "";

if (isset($_POST['asistan_sorgula'])) {
    $malzemeler_input = mysqli_real_escape_string($baglanti, $_POST['malzemeler']);

    $malzeme_listesi = explode(',', $malzemeler_input);
    

    $kosullar = [];
    foreach ($malzeme_listesi as $m) {
        $m = trim($m);
        if(!empty($m)) {
            $kosullar[] = "t.malzemeler LIKE '%$m%'";
        }
    }
    
    if (!empty($kosullar)) {
        $filtre = implode(' OR ', $kosullar);
        $sorgu = "SELECT t.*, k.kullanici_adi, 
                  (SELECT COUNT(*) FROM tarifler WHERE id = t.id AND ($filtre)) as eslesme_skoru
                  FROM tarifler t 
                  JOIN kullanicilar k ON t.ekleyen_id = k.id 
                  WHERE $filtre 
                  ORDER BY eslesme_skoru DESC LIMIT 5";
        $onerilen_tarifler = mysqli_query($baglanti, $sorgu);
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suna AI | Akıllı Mutfak Asistanı</title>
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

        body { background: var(--arkaplan); font-family: 'Poppins', sans-serif; color: var(--yazi); min-height: 100vh; }
        
        .ai-container { padding-top: 60px; padding-bottom: 60px; }

  
        .ai-card { 
            background: white; border-radius: 40px; padding: 50px; 
            box-shadow: 0 20px 60px rgba(128, 0, 0, 0.05); border: 1px solid var(--krem-koyu);
            position: relative; overflow: hidden;
        }
        .ai-card::before {
            content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 8px;
            background: linear-gradient(90deg, var(--bordo), var(--altin));
        }

        .ai-input { 
            border-radius: 25px; border: 2px solid var(--krem-koyu); padding: 25px; 
            font-size: 1.1rem; background-color: var(--arkaplan); color: var(--yazi);
            transition: 0.3s;
        }
        .ai-input:focus { 
            border-color: var(--bordo); box-shadow: 0 10px 25px rgba(128, 0, 0, 0.05); 
            background-color: white; outline: none; 
        }

        .btn-ai { 
            background: linear-gradient(135deg, var(--bordo) 0%, var(--bordo-acik) 100%); 
            color: white; border-radius: 20px; 
            padding: 18px 50px; font-weight: 700; border: none; transition: 0.4s;
            box-shadow: 0 10px 25px rgba(128, 0, 0, 0.2); letter-spacing: 1px;
        }
        .btn-ai:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 15px 35px rgba(212, 175, 55, 0.3); 
            color: white;
        }


        .recipe-box {
            background: white; border-radius: 20px; padding: 20px; margin-top: 15px;
            border-left: 5px solid var(--bordo); transition: 0.3s;
            border-top: 1px solid var(--krem-koyu);
            border-right: 1px solid var(--krem-koyu);
            border-bottom: 1px solid var(--krem-koyu);
        }
        .recipe-box:hover {
            transform: translateX(10px);
            background: var(--krem);
            border-color: var(--bordo);
        }

        .header-title { font-family: 'Playfair Display', serif; color: var(--bordo); font-weight: 800; }
        .header-title span { color: var(--altin); }

        .ai-icon-circle {
            width: 80px; height: 80px; background: var(--krem-koyu); color: var(--bordo);
            border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;
            font-size: 2.5rem; margin-bottom: 20px; border: 2px solid var(--altin);
        }
    </style>
</head>
<body>
    <div class="container ai-container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <div class="ai-icon-circle">
                    <i class="fas fa-robot"></i>
                </div>
                <h1 class="header-title display-4 mb-3">
                    Suna <span>AI</span>
                </h1>
                <p class="text-muted mb-5 fw-bold" style="letter-spacing: 1px;">AKILLI MUTFAK ASİSTANI</p>
                
                <div class="ai-card shadow-sm">
                    <form method="POST">
                        <textarea name="malzemeler" class="form-control ai-input mb-4" 
                                  placeholder="Elinizdeki malzemeleri virgülle ayırarak yazın... (Örn: tavuk, mantar, krema)" rows="3"><?= $malzemeler_input ?></textarea>
                        <button type="submit" name="asistan_sorgula" class="btn-ai text-uppercase">
                            Tarifleri Analiz Et <i class="fas fa-magic ms-2"></i>
                        </button>
                    </form>

                    <?php if ($onerilen_tarifler): ?>
                        <div class="mt-5 text-start">
                            <h5 class="fw-bold mb-4" style="color:var(--bordo)">
                                <i class="fas fa-sparkles text-warning me-2"></i>Senin için seçtiğim en iyi eşleşmeler:
                            </h5>
                            <?php while($tarif = mysqli_fetch_assoc($onerilen_tarifler)): ?>
                                <a href="tarif-detay.php?id=<?= $tarif['id'] ?>" class="text-decoration-none">
                                    <div class="recipe-box shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0 fw-bold" style="color:var(--bordo); font-size: 1.1rem;"><?= $tarif['baslik'] ?></h6>
                                            <span class="badge" style="background: var(--krem-koyu); color: var(--bordo); border: 1px solid var(--bordo);">
                                                <i class="fas fa-check-circle me-1"></i> Eşleşme Sağlandı
                                            </span>
                                        </div>
                                        <small class="text-muted" style="font-style: italic;">
                                            <?= mb_substr(strip_tags($tarif['malzemeler']), 0, 120) ?>...
                                        </small>
                                    </div>
                                </a>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mt-5 text-muted small">
                    <i class="fas fa-info-circle me-1"></i> Malzeme sayınız arttıkça Suna AI daha isabetli sonuçlar verecektir.
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>