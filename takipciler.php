<?php
session_start();
include 'baglanti.php';


if (!isset($_SESSION['kullanici_id']) || $_SESSION['kullanici_id'] != 1) {
    header("Location: panel.php");
    exit();
}

$kullanicilar = mysqli_query($baglanti, "SELECT * FROM kullanicilar WHERE id != 1 ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mutfak Dostları | Suna's Life</title>
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

   
        .user-card-premium { 
            background: white; border-radius: 30px; padding: 30px; 
            border: 1px solid var(--krem-koyu); 
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex; align-items: center; gap: 20px; position: relative; overflow: hidden;
            box-shadow: 0 10px 30px rgba(128, 0, 0, 0.02);
        }
        .user-card-premium:hover { 
            transform: translateY(-8px) scale(1.02); 
            box-shadow: 0 20px 40px rgba(128, 0, 0, 0.08); 
            border-color: var(--bordo);
        }

 
        .avatar-box {
            width: 70px; height: 70px; 
            background: linear-gradient(135deg, var(--bordo) 0%, var(--bordo-acik) 100%); 
            color: white; border-radius: 22px; 
            display: flex; align-items: center; justify-content: center; 
            font-size: 1.6rem; font-weight: 800; 
            box-shadow: 0 8px 15px rgba(128, 0, 0, 0.2);
            flex-shrink: 0;
            transform: rotate(-3deg);
        }


        .user-info h6 { color: var(--bordo); font-weight: 700; margin-bottom: 2px; font-size: 1.1rem; }
        .user-email { color: #5a4a42; font-size: 0.85rem; display: flex; align-items: center; gap: 6px; }
        .user-email i { color: var(--altin); font-size: 0.8rem; }


        .member-since {
            position: absolute; top: 15px; right: 20px;
            font-size: 0.65rem; color: var(--bordo); font-weight: 700; text-transform: uppercase;
            opacity: 0.5;
        }

        .header-title { color: var(--bordo); font-weight: 800; font-size: 2.2rem; font-family: 'Playfair Display', serif; }
        .header-title span { color: var(--altin); }
        .stats-summary { font-size: 0.95rem; color: var(--bordo-acik); font-weight: 600; margin-bottom: 40px; display: block; letter-spacing: 0.5px; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <div class="container-fluid">
            <div class="mb-5">
                <h2 class="header-title mb-1">
                    <i class="fas fa-users-crown me-3" style="color:var(--altin)"></i>Mutfak <span>Dostları</span>
                </h2>
                <span class="stats-summary">
                    <i class="fas fa-heart me-1" style="color: var(--bordo);"></i> Toplam <?= mysqli_num_rows($kullanicilar) ?> takipçi mutfağında seni izliyor.
                </span>
            </div>
            
            <div class="row g-4">
                <?php if(mysqli_num_rows($kullanicilar) > 0): ?>
                    <?php while($u = mysqli_fetch_assoc($kullanicilar)): ?>
                        <div class="col-xl-4 col-md-6">
                            <div class="user-card-premium shadow-sm">
                                <div class="member-since">
                                    <i class="far fa-calendar-alt me-1"></i>Üye
                                </div>
                                
                                <div class="avatar-box">
                                    <?= strtoupper(substr($u['kullanici_adi'], 0, 1)) ?>
                                </div>
                                
                                <div class="user-info">
                                    <h6><?= htmlspecialchars($u['kullanici_adi']) ?></h6>
                                    <div class="user-email">
                                        <i class="fas fa-envelope"></i> <?= htmlspecialchars($u['email']) ?>
                                    </div>
                                    <div class="mt-2">
                                        <span class="badge rounded-pill" style="background: var(--krem-koyu); color: var(--bordo); border: 1px solid rgba(128, 0, 0, 0.1); font-size: 0.65rem; padding: 6px 12px; font-weight: 700;">
                                            MUTFAK DOSTU
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="p-5 text-center bg-white rounded-5 shadow-sm" style="border: 2px dashed var(--krem-koyu);">
                            <i class="fas fa-user-friends fa-4x mb-3" style="color: var(--bordo); opacity: 0.1;"></i>
                            <h4 class="fw-bold" style="color: var(--bordo); opacity: 0.5;">Henüz kimse mutfağa girmedi...</h4>
                            <p class="text-muted mb-0">Tariflerini paylaştıkça burası dolup taşacak!</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>