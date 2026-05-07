<?php
session_start();
include 'baglanti.php';

if (!isset($_SESSION['kullanici_id']) || $_SESSION['kullanici_id'] != 1) {
    header("Location: panel.php");
    exit();
}

$mesajlar = mysqli_query($baglanti, "SELECT m.*, k.kullanici_adi, k.email FROM mesajlar m 
          JOIN kullanicilar k ON m.gonderen_id = k.id 
          WHERE m.alici_id = 1 ORDER BY m.tarih DESC");
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelen Mesajlar | Suna's Life</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .msg-card-premium { 
            background: white; border-radius: 25px; 
            border: 1px solid var(--krem-koyu); 
            transition: 0.4s ease; position: relative;
            box-shadow: 0 10px 30px rgba(128, 0, 0, 0.02);
            height: 100%;
        }
        .msg-card-premium:hover { 
            transform: translateY(-8px); 
            box-shadow: 0 20px 45px rgba(128, 0, 0, 0.08);
            border-color: var(--bordo);
        }

    
        .msg-avatar { 
            width: 55px; height: 55px; 
            background: linear-gradient(135deg, var(--bordo) 0%, var(--bordo-acik) 100%); 
            color: white; border-radius: 18px; 
            display: flex; align-items: center; justify-content: center; 
            font-size: 1.3rem; font-weight: 800;
            box-shadow: 0 5px 15px rgba(128, 0, 0, 0.2);
        }

        /* TARİH ETİKETİ */
        .msg-time { 
            font-size: 0.7rem; color: var(--bordo); 
            background: var(--krem); padding: 5px 12px; 
            border-radius: 10px; font-weight: 600;
            border: 1px solid var(--krem-koyu) !important;
        }

   
        .msg-content { 
            color: #5a4a42; font-size: 0.95rem; 
            line-height: 1.6; font-style: italic;
            background: var(--arkaplan);
            border-left: 4px solid var(--bordo);
        }

        .header-title { color: var(--bordo); font-weight: 800; font-size: 2.2rem; }
        .msg-count-badge { background: var(--bordo); color: white; font-size: 0.8rem; padding: 8px 18px; border-radius: 50px; font-weight: 700; border: 1px solid var(--altin); }
        
        /* ÖZEL İKONLAR */
        .action-icon { color: var(--altin); font-size: 1.1rem; cursor: pointer; transition: 0.3s; text-decoration: none; }
        .action-icon:hover { transform: scale(1.2); color: var(--bordo); }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h2 class="header-title m-0">
                <i class="fas fa-envelope-open-text me-3" style="color:var(--altin)"></i>Gelen <span style="color:var(--altin)">Mesajlar</span>
            </h2>
            <div class="msg-count-badge shadow-sm">
                <i class="fas fa-inbox me-2"></i><?= mysqli_num_rows($mesajlar) ?> Yeni İleti
            </div>
        </div>

        <div class="row g-4">
            <?php if(mysqli_num_rows($mesajlar) > 0): ?>
                <?php while($m = mysqli_fetch_assoc($mesajlar)): ?>
                    <div class="col-xl-6">
                        <div class="msg-card-premium p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="msg-avatar">
                                        <?= strtoupper(substr($m['kullanici_adi'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold" style="color: var(--bordo);"><?= htmlspecialchars($m['kullanici_adi']) ?></h6>
                                        <small class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($m['email']) ?></small>
                                    </div>
                                </div>
                                <span class="msg-time">
                                    <i class="far fa-clock me-1"></i><?= date('d.m.Y H:i', strtotime($m['tarih'])) ?>
                                </span>
                            </div>

                            <div class="msg-content p-3 rounded-4">
                                <?= nl2br(htmlspecialchars($m['mesaj_icerik'])) ?>
                            </div>

                            <div class="d-flex justify-content-end gap-3 mt-3">
                                <a href="mailto:<?= $m['email'] ?>" title="E-posta ile yanıtla" class="action-icon">
                                    <i class="fas fa-reply"></i>
                                </a>
                                <a href="mesaj-sil.php?id=<?= $m['id'] ?>" title="Mesajı sil" class="action-icon text-danger" onclick="return confirm('Bu mesajı arşive göndermek yerine tamamen silmek istediğine emin misin şefim?')">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <div class="icon-box mx-auto mb-3" style="width:120px; height:120px; background: var(--krem-koyu); color: var(--bordo); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:4rem; opacity:0.3;">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h4 class="text-muted fw-bold">Posta kutun şu an boş.</h4>
                    <p class="text-muted">Yeni tariflerin için gelecek yorumları ve soruları bekliyoruz!</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>