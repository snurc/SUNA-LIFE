<?php
session_start();
include 'baglanti.php';

if (!isset($_SESSION['kullanici_id'])) {
    header("Location: giris.php");
    exit();
}


$sorgu = "SELECT t.*, k.kullanici_adi FROM tarifler t 
          JOIN kullanicilar k ON t.ekleyen_id = k.id 
          WHERE t.video_url != '' AND t.video_url IS NOT NULL 
          ORDER BY t.id DESC";
$tarifler = mysqli_query($baglanti, $sorgu);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videolu Tarifler | Suna's Life</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

        body { background-color: var(--arkaplan); font-family: 'Poppins', sans-serif; margin: 0; display: flex; color: var(--yazi); }
        .main-content { flex-grow: 1; padding: 40px; margin-left: 280px; min-height: 100vh; }

        .video-card-premium {
            background: white; border-radius: 30px; border: 1px solid var(--krem-koyu);
            box-shadow: 0 10px 30px rgba(128, 0, 0, 0.02); transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden; position: relative;
        }
        .video-card-premium:hover { 
            transform: translateY(-12px); 
            box-shadow: 0 25px 50px rgba(128, 0, 0, 0.1); 
            border-color: var(--bordo);
        }


        .video-thumbnail { height: 220px; position: relative; overflow: hidden; border-bottom: 4px solid var(--altin); }
        .video-thumbnail img { transition: 0.6s ease; width: 100%; height: 100%; object-fit: cover; }
        .video-card-premium:hover .video-thumbnail img { transform: scale(1.1); filter: brightness(0.6); }

    
        .play-overlay {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%);
            color: white; font-size: 4rem; opacity: 0; transition: 0.4s ease; z-index: 2;
            text-shadow: 0 0 20px rgba(0,0,0,0.5);
        }
        .video-card-premium:hover .play-overlay { opacity: 1; }

        
        .video-body { padding: 25px; background: white; }
        .video-title { font-weight: 700; color: var(--bordo); margin-bottom: 12px; font-size: 1.15rem; line-height: 1.4; }
        .chef-name { font-size: 0.8rem; color: #5a4a42; font-weight: 600; }
        .chef-name span { color: var(--bordo); }
        
        .badge-video { 
            background: var(--krem-koyu); color: var(--bordo); 
            border-radius: 10px; padding: 6px 15px; font-weight: 700; font-size: 0.65rem; 
            margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px;
            border: 1px solid rgba(128, 0, 0, 0.05);
        }

  
        .btn-watch {
            background: linear-gradient(135deg, var(--bordo) 0%, var(--bordo-acik) 100%);
            color: white; border: none; border-radius: 18px;
            padding: 14px; font-weight: 700; width: 100%; transition: 0.3s;
            margin-top: 20px; letter-spacing: 1.5px; font-size: 0.8rem;
            box-shadow: 0 5px 15px rgba(128, 0, 0, 0.2);
        }
        .btn-watch:hover { background: var(--altin); color: var(--bordo); transform: scale(1.02); box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3); }

        .header-title { font-family: 'Playfair Display', serif; color: var(--bordo); font-weight: 800; font-size: 2.5rem; margin-bottom: 40px; }
        .header-title span { color: var(--altin); }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <h2 class="header-title">
            <i class="fas fa-play-circle me-3" style="color:var(--altin)"></i>Videolu <span>Lezzetler</span>
        </h2>

        <div class="row g-4">
            <?php while($row = mysqli_fetch_assoc($tarifler)): ?>
                <div class="col-xl-4 col-md-6">
                    <div class="card video-card-premium h-100">
                        <div class="video-thumbnail">
                            <?php 
                             
                                $vid = "";
                                if (strpos($row['video_url'], 'v=') !== false) {
                                    parse_str(parse_url($row['video_url'], PHP_URL_QUERY), $v); 
                                    $vid = $v['v'] ?? ''; 
                                } elseif (strpos($row['video_url'], 'youtu.be/') !== false) {
                                    $vid = substr(parse_url($row['video_url'], PHP_URL_PATH), 1);
                                } else {
                                    $vid = basename($row['video_url']); 
                                }
                            ?>
                            <img src="https://img.youtube.com/vi/<?= $vid ?>/maxresdefault.jpg" 
                                 onerror="this.src='https://img.youtube.com/vi/<?= $vid ?>/hqdefault.jpg'">
                            
                            <div class="play-overlay">
                                <i class="fas fa-play-circle"></i>
                            </div>
                        </div>

                        <div class="video-body d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge-video"><?= htmlspecialchars($row['kategori']) ?></span>
                                    <small class="text-muted fw-bold" style="font-size:0.65rem;">
                                        <i class="far fa-calendar-alt me-1 text-warning"></i><?= date('d.m.Y') ?>
                                    </small>
                                </div>
                                <h6 class="video-title"><?= htmlspecialchars($row['baslik']) ?></h6>
                                <div class="chef-name">
                                    <i class="fas fa-hat-chef me-1 text-warning"></i> Şef: <span><?= htmlspecialchars($row['kullanici_adi']) ?></span>
                                </div>
                            </div>
                            <a href="tarif-detay.php?id=<?= $row['id'] ?>" class="btn btn-watch text-uppercase">
                                <i class="fas fa-utensils me-2"></i> İZLE VE HAZIRLA
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>