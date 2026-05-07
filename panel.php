<?php
session_start();
include 'baglanti.php'; 

if (!isset($_SESSION['kullanici_id'])) {
    header("Location: giris.php");
    exit();
}

$kullanici_id = $_SESSION['kullanici_id'];

$toplam_tarif = mysqli_fetch_assoc(mysqli_query($baglanti, "SELECT COUNT(*) as t FROM tarifler"))['t'] ?? 0;
$toplam_uye = mysqli_fetch_assoc(mysqli_query($baglanti, "SELECT COUNT(*) as u FROM kullanicilar"))['u'] ?? 0;
$son_tarif = mysqli_fetch_assoc(mysqli_query($baglanti, "SELECT id, baslik FROM tarifler ORDER BY id DESC LIMIT 1"));

$gunun_tarifi_sorgu = mysqli_query($baglanti, "SELECT id, baslik, kategori, tarif_resim FROM tarifler ORDER BY RAND() LIMIT 1");
$gunun_tarifi = mysqli_fetch_assoc($gunun_tarifi_sorgu);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suna's Life | Mutfak Paneli</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --bordo: #800000; --bordo-acik: #A52A2A; --altin: #D4AF37; --krem: #FFFBF2; --krem-koyu: #F5E6D3; --yazi: #2D1B1B; }
        body { background-color: var(--krem); font-family: 'Poppins', sans-serif; color: var(--yazi); margin: 0; }
        .main-content { padding: 40px; margin-left: 280px; min-height: 100vh; }

        /* BANNER */
        .welcome-banner { 
            background: linear-gradient(135deg, var(--bordo) 0%, var(--bordo-acik) 100%); 
            border-radius: 40px; color: white; padding: 60px; margin-bottom: 40px; 
            box-shadow: 0 15px 35px rgba(128, 0, 0, 0.15); border-bottom: 5px solid var(--altin);
            position: relative; overflow: hidden;
        }
        .banner-img { position: absolute; right: -20px; bottom: -20px; opacity: 0.15; font-size: 15rem; transform: rotate(-15deg); }

      
        .user-hero-card {
            background: white; border-radius: 35px; padding: 0; overflow: hidden;
            display: flex; border: 1px solid var(--krem-koyu); box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            margin-bottom: 40px; min-height: 300px;
        }
        .user-hero-text { padding: 50px; flex: 1; }
        .user-hero-img { width: 45%; background: url('https://images.unsplash.com/photo-1556910103-1c02745aae4d?q=80&w=1000') center/cover; }

     
        .stat-card {
            background: white; border-radius: 25px; padding: 25px; border: 1px solid var(--krem-koyu);
            display: flex; align-items: center; gap: 20px; transition: 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); border-color: var(--altin); }
        .stat-icon { width: 55px; height: 55px; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; background: var(--krem); color: var(--bordo); }

        /* GÜNÜN TARİFİ */
        .promo-card {
            background: linear-gradient(45deg, #1A0000, var(--bordo));
            border-radius: 40px; padding: 50px; color: white; position: relative; overflow: hidden;
            border: 1px solid var(--altin); height: 100%;
        }

        .quick-action-card { 
            background: white; border-radius: 25px; padding: 20px; border: 1px solid var(--krem-koyu); 
            text-decoration: none; display: flex; align-items: center; transition: 0.3s;
        }
        .quick-action-card:hover { background: var(--krem); border-color: var(--bordo); transform: translateX(10px); }
        
        .header-title { font-family: 'Playfair Display', serif; font-weight: 800; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        
        <div class="welcome-banner d-flex justify-content-between align-items-center">
            <div style="z-index: 2;">
                <h1 class="header-title display-4 mb-2">Şef Suna'nın <span style="color:var(--altin)">Mutfağı</span></h1>
                <p class="lead opacity-75 mb-0">Hoş geldin <?= $_SESSION['kullanici_adi'] ?>, bugün hangi lezzeti keşfedeceğiz?</p>
            </div>
            <i class="fas fa-utensils banner-img"></i>
        </div>

        <div class="container-fluid p-0">

            <?php if($kullanici_id == 1): ?>
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-book-open"></i></div>
                            <div><h3 class="fw-bold mb-0"><?= $toplam_tarif ?></h3><small class="text-muted text-uppercase">Toplam Tarif</small></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-users"></i></div>
                            <div><h3 class="fw-bold mb-0"><?= $toplam_uye ?></h3><small class="text-muted text-uppercase">Aktif Şef</small></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-star"></i></div>
                            <div><h6 class="fw-bold mb-0 text-truncate" style="max-width: 150px;"><?= $son_tarif['baslik'] ?></h6><small class="text-muted text-uppercase">En Yeni Lezzet</small></div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="user-hero-card">
                    <div class="user-hero-text">
                        <span class="badge mb-3" style="background: var(--krem-koyu); color: var(--bordo); padding: 8px 20px; border-radius: 50px;">Mutfak Notu</span>
                        <h2 class="header-title mb-3" style="color: var(--bordo);">Kendi Mutfağının Sanatçısı Ol!</h2>
                        <p class="text-muted mb-4">Suna'nın özel reçeteleriyle sofranı bir sanat galerisine dönüştür. Bugün deneyeceğin her tarif, yeni bir hikaye başlatacak.</p>
                        <a href="resimli-tarifler.php" class="btn btn-dark rounded-pill px-5 py-3 fw-bold" style="background: var(--bordo); border:none;">TARİFLERİ KEŞFET <i class="fas fa-arrow-right ms-2"></i></a>
                    </div>
                    <div class="user-hero-img"></div>
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <div class="<?= ($kullanici_id == 1) ? 'col-lg-8' : 'col-lg-7' ?>">
                    <div class="promo-card shadow-lg">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill fw-bold">GÜNÜN ŞANSLI TARİFİ</span>
                                <h2 class="header-title display-5 mb-3"><?= $gunun_tarifi['baslik'] ?></h2>
                                <p class="opacity-75 mb-4">Mutfakta ne pişireceğine karar veremediysen, Suna'nın senin için seçtiği bu özel tatta gizli bir sanat var.</p>
                                <a href="tarif-detay.php?id=<?= $gunun_tarifi['id'] ?>" class="btn btn-light btn-lg rounded-pill px-5 fw-bold" style="color: var(--bordo);">TARİFE GİT <i class="fas fa-magic ms-2"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="<?= ($kullanici_id == 1) ? 'col-lg-4' : 'col-lg-5' ?>">
                    <div class="management-card h-100">
                        <h5 class="header-title mb-4" style="color: var(--bordo);"><i class="fas fa-bolt me-2 text-warning"></i> Hızlı Erişim</h5>
                        <div class="d-grid gap-3">
                            <a href="suna-ai.php" class="quick-action-card">
                                <div class="stat-icon me-3"><i class="fas fa-robot"></i></div>
                                <div><div class="fw-bold text-dark">Suna AI Asistan</div><small class="text-muted">Malzemeni yaz, tarif gelsin</small></div>
                            </a>
                            <a href="tarif-ekle.php" class="quick-action-card">
                                <div class="stat-icon me-3"><i class="fas fa-plus-circle"></i></div>
                                <div><div class="fw-bold text-dark">Tarif Yayınla</div><small class="text-muted">Mutfak arşivini genişlet</small></div>
                            </a>
                            <?php if($kullanici_id != 1): ?>
                            <a href="mesaj-gonder.php" class="quick-action-card">
                                <div class="stat-icon me-3"><i class="fas fa-envelope"></i></div>
                                <div><div class="fw-bold text-dark">Şefe Soru Sor</div><small class="text-muted">Bir tarif hakkında destek al</small></div>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>