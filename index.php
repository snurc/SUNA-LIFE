<?php
include 'baglanti.php';
mysqli_set_charset($baglanti, "utf8mb4");


$stats_query = mysqli_query($baglanti, "SELECT 
    (SELECT COUNT(id) FROM tarifler WHERE onay_durumu = 1) as toplam_tarif,
    (SELECT COUNT(id) FROM kullanicilar) as toplam_sef");
$stats = mysqli_fetch_assoc($stats_query);


$vitrin_sorgu = mysqli_query($baglanti, "SELECT t.*, k.kullanici_adi FROM tarifler t 
                JOIN kullanicilar k ON t.ekleyen_id = k.id 
                WHERE t.onay_durumu = 1 
                ORDER BY t.id DESC LIMIT 6");


$akilli_sozluk = [
    'magnolia' => 'https://images.unsplash.com/photo-1488477181946-6428a0291777?q=80&w=800',
    'risotto'  => 'https://images.unsplash.com/photo-1633337474564-1d8bf6164213?q=80&w=800',
    'mercimek' => 'https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=800',
    'çorba'    => 'https://images.unsplash.com/photo-1548943487-a2e4f43b4850?q=80&w=800',
    'baget'    => 'https://images.unsplash.com/photo-1598373182133-52452f7691ef?q=80&w=800',
    'ekmek'    => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?q=80&w=800',
    'kabak'    => 'https://images.unsplash.com/photo-1572360678223-95692dd54f7b?q=80&w=800',
    'fvf'      => 'https://images.unsplash.com/photo-1551024506-0bccd828d307?q=80&w=800',
];

$kategori_yedekleri = [
    'Tatlılar' => 'https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?q=80&w=800',
    'Çorbalar' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?q=80&w=800',
    'Hamur İşleri' => 'https://images.unsplash.com/photo-1589367920969-ab8e050eb046?q=80&w=800',
    'Makarnalar' => 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?q=80&w=800',
    'Varsayilan' => 'https://images.unsplash.com/photo-1495195134817-a165d42929ce?q=80&w=800'
];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suna's Life | Premium Gastro Atölyesi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,900&family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
      
        :root { 
            --bordo: #800000;       
            --bordo-koyu: #4A0000;  
            --altin: #D4AF37;       
            --krem: #FFFDF9;        
            --yazi: #2D1B1B;       
        }
        
        body { font-family: 'Poppins', sans-serif; background-color: var(--krem); color: var(--yazi); overflow-x: hidden; }
   
        .navbar-custom { background: transparent; transition: 0.4s ease-in-out; padding: 25px 0; }
        .navbar-custom.scrolled { background: rgba(255, 253, 249, 0.95); backdrop-filter: blur(15px); padding: 15px 0; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .navbar-brand { font-family: 'Playfair Display', serif; font-weight: 900; font-size: 2.2rem; color: white !important; transition: 0.4s; }
        .navbar-custom.scrolled .navbar-brand { color: var(--bordo) !important; }
        .navbar-brand span { color: var(--altin); }
        
        .nav-link { color: white; font-weight: 400; letter-spacing: 1px; margin: 0 15px; transition: 0.3s; text-transform: uppercase; font-size: 0.85rem; }
        .navbar-custom.scrolled .nav-link { color: var(--yazi); }
        .nav-link:hover { color: var(--altin) !important; }
        
        .btn-outline-custom { border: 1px solid var(--altin); color: var(--altin); border-radius: 0; padding: 10px 30px; font-weight: 500; transition: 0.4s; letter-spacing: 1px; }
        .navbar-custom.scrolled .btn-outline-custom { border-color: var(--bordo); color: var(--bordo); }
        .btn-outline-custom:hover { background: var(--altin); color: white !important; border-color: var(--altin) !important; }

        .hero { 
            height: 100vh; position: relative; display: flex; align-items: center; justify-content: center;
            background: url('https://images.unsplash.com/photo-1556910103-1c02745aae4d?q=80&w=2000') center/cover no-repeat fixed;
        }
        .hero::before { content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); }
        
        .hero-glass-box {
            position: relative; z-index: 1; background: rgba(74, 0, 0, 0.4); /* Hafif bordo yansıması eklendi */
            backdrop-filter: blur(12px); border: 1px solid rgba(212, 175, 55, 0.3);
            padding: 80px 60px; border-radius: 2px; text-align: center; max-width: 900px;
            animation: fadeIn 1.5s ease-out forwards;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        
        .hero-glass-box h1 { font-family: 'Playfair Display', serif; font-size: 5.5rem; font-weight: 400; line-height: 1.1; margin-bottom: 25px; color: white; }
        .hero-glass-box h1 i { color: var(--altin); font-style: italic; font-weight: 700; }
        .hero-glass-box p { font-size: 1.1rem; font-weight: 300; letter-spacing: 3px; color: rgba(255,255,255,0.9); text-transform: uppercase; margin-bottom: 40px; }
        .btn-hero { background: var(--altin); color: var(--bordo-koyu); padding: 15px 45px; font-weight: 700; letter-spacing: 2px; text-decoration: none; display: inline-block; transition: 0.4s; border-radius: 0; }
        .btn-hero:hover { background: white; transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.3); }

      
        .floating-stats-wrapper { position: relative; margin-top: -60px; z-index: 10; padding: 0 20px; }
        .stats-inner { background: white; box-shadow: 0 25px 50px rgba(128, 0, 0, 0.05); padding: 50px 0; border-radius: 2px; border-bottom: 3px solid var(--altin); }
        .stat-item { text-align: center; position: relative; }
        .stat-item:not(:last-child)::after { content: ''; position: absolute; right: 0; top: 10%; height: 80%; width: 1px; background: rgba(0,0,0,0.05); }
        .stat-item h2 { font-family: 'Playfair Display', serif; font-size: 3.5rem; font-weight: 900; color: var(--bordo); margin-bottom: 5px; }
        .stat-item span { font-size: 0.8rem; font-weight: 600; letter-spacing: 3px; color: #888; text-transform: uppercase; }

        .dark-features { background: var(--bordo); color: white; padding: 150px 0 100px; margin-top: -50px; }
        .feature-item { padding: 40px; transition: 0.4s; border: 1px solid transparent; }
        .feature-item:hover { border-color: rgba(212, 175, 55, 0.4); background: rgba(255,255,255,0.03); transform: translateY(-10px); }
        .f-icon { font-size: 3rem; color: var(--altin); margin-bottom: 30px; display: block; }
        .feature-item h4 { font-family: 'Playfair Display', serif; font-size: 1.8rem; margin-bottom: 20px; }
        .feature-item p { font-weight: 300; opacity: 0.85; line-height: 1.8; font-size: 0.95rem; }

       
        .showcase-section { padding: 120px 0; background: var(--krem); }
        .section-header { text-align: center; margin-bottom: 80px; }
        .section-header span { color: var(--altin); font-weight: 700; letter-spacing: 4px; font-size: 0.8rem; text-transform: uppercase; display: block; margin-bottom: 15px; }
        .section-header h2 { font-family: 'Playfair Display', serif; font-size: 4rem; font-weight: 900; color: var(--bordo); line-height: 1; }

        .mag-card { background: white; border: none; border-radius: 0; transition: 0.5s; height: 100%; display: flex; flex-direction: column; position: relative; overflow: hidden; }
        .mag-card:hover { transform: scale(1.02); box-shadow: 0 30px 60px rgba(128, 0, 0, 0.08); z-index: 2; }
        
        .img-container { position: relative; height: 300px; overflow: hidden; }
        .img-container img { width: 100%; height: 100%; object-fit: cover; transition: 1s ease; filter: brightness(0.9); }
        .mag-card:hover .img-container img { transform: scale(1.1); filter: brightness(1); }
        
        .diet-tag { position: absolute; top: 20px; left: 20px; background: var(--altin); color: var(--bordo-koyu); padding: 5px 15px; font-size: 0.7rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; }

        .mag-body { padding: 40px; background: white; flex-grow: 1; display: flex; flex-direction: column; border: 1px solid #f5f5f5; border-top: none; }
        .mag-cat { color: #aaa; font-size: 0.75rem; font-weight: 600; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 15px; }
        .mag-title { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: var(--bordo); margin-bottom: 20px; line-height: 1.2; }
        .mag-chef { font-style: italic; color: #777; font-size: 0.9rem; margin-bottom: 30px; }
        
        .mag-footer { margin-top: auto; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f0f0f0; padding-top: 25px; }
        .kcal-box { font-weight: 500; color: #999; font-size: 0.85rem; }
        .read-link { color: var(--bordo); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 0.8rem; text-decoration: none; transition: 0.3s; }
        .read-link:hover { color: var(--altin); }

        /* --- GÖRKEMLİ FOOTER (Altın Bordo Kombini) --- */
        .cta-section { background: var(--altin); padding: 80px 0; text-align: center; }
        .cta-section h2 { font-family: 'Playfair Display', serif; font-size: 3rem; color: var(--bordo-koyu); font-weight: 900; margin-bottom: 20px; }
        .cta-section .btn-cta { border: 2px solid var(--bordo-koyu); color: var(--bordo-koyu); padding: 12px 40px; font-weight: 700; text-decoration: none; text-transform: uppercase; letter-spacing: 2px; transition: 0.4s; }
        .cta-section .btn-cta:hover { background: var(--bordo-koyu); color: white; }

        .footer { background: var(--bordo-koyu); color: white; padding: 80px 0 30px; }
        .footer-brand { font-family: 'Playfair Display', serif; font-size: 2.5rem; font-weight: 900; margin-bottom: 20px; display: inline-block; }
        .footer-brand span { color: var(--altin); }
        .social-links a { display: inline-flex; width: 45px; height: 45px; border: 1px solid rgba(255,255,255,0.2); align-items: center; justify-content: center; color: white; margin-right: 15px; border-radius: 50%; transition: 0.4s; text-decoration: none; }
        .social-links a:hover { background: var(--altin); border-color: var(--altin); color: var(--bordo-koyu); transform: translateY(-5px); }
        .f-title { font-size: 0.85rem; color: var(--altin); letter-spacing: 3px; text-transform: uppercase; margin-bottom: 30px; }
        .f-list { list-style: none; padding: 0; margin: 0; }
        .f-list li { margin-bottom: 15px; }
        .f-list a { color: rgba(255,255,255,0.7); text-decoration: none; transition: 0.3s; font-weight: 300; }
        .f-list a:hover { color: white; padding-left: 8px; }
        .copyright { text-align: center; padding-top: 40px; margin-top: 60px; border-top: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.5); font-size: 0.85rem; letter-spacing: 1px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top navbar-custom" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="index.php">Suna's <span>Life</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars" style="color: var(--altin);"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Atölye</a></li>
                    <li class="nav-item"><a class="nav-link" href="#vitrin">Koleksiyon</a></li>
                    <li class="nav-item"><a class="nav-link" href="#hakkimizda">Felsefemiz</a></li>
                </ul>
                <div class="d-flex align-items-center gap-4">
                    <a href="giris.php" class="nav-link d-none d-lg-block" style="margin:0;">GİRİŞ YAP</a>
                    <a href="kayit.php" class="btn btn-outline-custom">ÖNLÜK AL</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="container d-flex justify-content-center">
            <div class="hero-glass-box">
                <h1>Mutfakta <br><i>Yüksek Dikiş</i></h1>
                <p>Gastronominin sınırlarını yapay zeka ile zorlayan seçkin atölye.</p>
                <a href="#vitrin" class="btn-hero">KOLEKSİYONU KEŞFET</a>
            </div>
        </div>
    </section>

    <div class="container floating-stats-wrapper">
        <div class="stats-inner row g-0">
            <div class="col-md-4 stat-item">
                <h2><?= $stats['toplam_tarif'] ?></h2>
                <span>Özel Reçete</span>
            </div>
            <div class="col-md-4 stat-item">
                <h2><?= $stats['toplam_sef'] ?></h2>
                <span>Gastronomi Sanatçısı</span>
            </div>
            <div class="col-md-4 stat-item">
                <h2><i class="fas fa-infinity" style="font-size: 2.5rem;"></i></h2>
                <span>Sonsuz Varyasyon</span>
            </div>
        </div>
    </div>

    <section id="hakkimizda" class="dark-features">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-item">
                        <i class="fas fa-brain f-icon"></i>
                        <h4>Suna AI Zekası</h4>
                        <p>Sıradan malzemelerinizi girin, yapay zeka motorumuz onları Michelin standartlarında bir tabağa dönüştürecek ilhamı saniyeler içinde sunsun.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-item">
                        <i class="fas fa-book-journal-whills f-icon"></i>
                        <h4>Küratörlü Arşiv</h4>
                        <p>Sadece denenmiş ve atölye şefleri tarafından onaylanmış, hataya yer bırakmayan kusursuz gastronomi arşivine sınırsız erişim sağlayın.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-item">
                        <i class="fas fa-wine-glass-empty f-icon"></i>
                        <h4>Elit Topluluk</h4>
                        <p>Sizinle aynı tutkuyu paylaşan şeflerle iletişim kurun, tekniklerinizi tartışın ve mutfak sanatındaki kendi kişisel imzanızı yaratın.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="vitrin" class="showcase-section">
        <div class="container">
            <div class="section-header">
                <span>Öne Çıkanlar</span>
                <h2>Son <i>Kreasyonlar</i></h2>
            </div>
            
            <div class="row g-5">
                <?php while($tarif = mysqli_fetch_assoc($vitrin_sorgu)): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="mag-card">
                        <div class="img-container">
                            <?php 
                                $kategori = $tarif['kategori'];
                                $baslik = mb_strtolower($tarif['baslik'], 'UTF-8');
                                $secilen_gorsel = "";
                                
                                foreach ($akilli_sozluk as $kelime => $resim_url) {
                                    if (strpos($baslik, $kelime) !== false) {
                                        $secilen_gorsel = $resim_url;
                                        break; 
                                    }
                                }
                                
                                if ($secilen_gorsel == "") {
                                    $secilen_gorsel = isset($kategori_yedekleri[$kategori]) ? $kategori_yedekleri[$kategori] : $kategori_yedekleri['Varsayilan'];
                                }
                                
                                if (!empty($tarif['tarif_resim']) && strpos($tarif['tarif_resim'], 'http') === false) {
                                    $secilen_gorsel = 'resimler/' . trim($tarif['tarif_resim']);
                                }
                            ?>
                            
                            <img src="<?= $secilen_gorsel ?>" alt="<?= $tarif['baslik'] ?>" onerror="this.onerror=null; this.src='<?= isset($kategori_yedekleri[$kategori]) ? $kategori_yedekleri[$kategori] : $kategori_yedekleri['Varsayilan'] ?>';">
                            
                            <?php if(!empty($tarif['diyet_tipi'])): ?>
                                <span class="diet-tag"><?= $tarif['diyet_tipi'] ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mag-body">
                            <span class="mag-cat"><?= $tarif['kategori'] ?></span>
                            <h3 class="mag-title"><?= mb_substr($tarif['baslik'], 0, 45) . (mb_strlen($tarif['baslik']) > 45 ? '...' : '') ?></h3>
                            <div class="mag-chef">Şef: <?= $tarif['kullanici_adi'] ?></div>
                            
                            <div class="mag-footer">
                                <div class="kcal-box">
                                    <i class="fas fa-fire" style="color:var(--bordo); margin-right:5px;"></i> 
                                    <?= ($tarif['kalori'] > 0) ? $tarif['kalori'] . ' Kcal' : 'Bilinmiyor' ?>
                                </div>
                                <a href="giris.php" class="read-link">REÇETEYİ AÇ</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <h2>Kendi Hikayenizi Yazın</h2>
            <p class="mb-4 text-dark opacity-75">Binlerce seçkin şefin arasına katılın ve mutfağın kurallarını yeniden belirleyin.</p>
            <a href="kayit.php" class="btn-cta">ÜCRETSİZ KATILIN</a>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-5">
                    <div class="footer-brand">Suna's <span>Life</span></div>
                    <p class="mb-4" style="color: rgba(255,255,255,0.7); font-weight: 300; line-height: 1.8;">Gastronominin sınırlarını yapay zeka ile zorlayan, sanat ve lezzeti tek bir tabakta birleştiren seçkin şefler kulübü.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-pinterest-p"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 offset-lg-1 col-md-4">
                    <h5 class="f-title">Koleksiyon</h5>
                    <ul class="f-list">
                        <li><a href="#">Dünya Mutfağı</a></li>
                        <li><a href="#">İmza Tatlılar</a></li>
                        <li><a href="#">Özel Diyetler</a></li>
                        <li><a href="#">Şefin Tavsiyeleri</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5 class="f-title">Atölye</h5>
                    <ul class="f-list">
                        <li><a href="#hakkimizda">Felsefemiz</a></li>
                        <li><a href="#">Suna AI Nedir?</a></li>
                        <li><a href="#">Şef Başvurusu</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5 class="f-title">Destek</h5>
                    <ul class="f-list">
                        <li><a href="giris.php">Giriş Yap</a></li>
                        <li><a href="kayit.php">Kayıt Ol</a></li>
                        <li><a href="#">İletişim</a></li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                &copy; <?= date("Y") ?> Suna's Life Gastro Sanat Atölyesi. Kodların ve lezzetlerin ustası: Çağrı.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener('scroll', function() {
            var navbar = document.getElementById('mainNav');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>