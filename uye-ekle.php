<?php
session_start();
include 'baglanti.php';


if (!isset($_SESSION['kullanici_id']) || $_SESSION['kullanici_id'] != 1) {
    header("Location: panel.php");
    exit();
}

$mesaj = "";
$mesaj_turu = "";

if (isset($_POST['uye_kaydet'])) {
    $kullanici_adi = mysqli_real_escape_string($baglanti, $_POST['kullanici_adi']);
    $email = mysqli_real_escape_string($baglanti, $_POST['email']);
    $sifre = $_POST['sifre'];


    $kontrol = mysqli_query($baglanti, "SELECT * FROM kullanicilar WHERE email = '$email'");
    
    if (mysqli_num_rows($kontrol) > 0) {
        $mesaj = "Bu e-posta adresi zaten kayıtlı!";
        $mesaj_turu = "danger";
    } else {
        $hashed_password = password_hash($sifre, PASSWORD_DEFAULT);
        $ekle = "INSERT INTO kullanicilar (kullanici_adi, email, parola) VALUES ('$kullanici_adi', '$email', '$hashed_password')";
        
        if (mysqli_query($baglanti, $ekle)) {
            $mesaj = "Yeni şef başarıyla mutfağa eklendi!";
            $mesaj_turu = "success";
        } else {
            $mesaj = "Sistem hatası: Üye eklenemedi.";
            $mesaj_turu = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Üye Ekle | Suna's Life</title>
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
        
        /* FORM KARTI TASARIMI */
        .form-card {
            background: white; border-radius: 40px; padding: 50px;
            max-width: 650px; margin: 0 auto;
            box-shadow: 0 20px 60px rgba(128, 0, 0, 0.05);
            border: 1px solid var(--krem-koyu);
            position: relative;
            overflow: hidden;
        }

        .form-card::before {
            content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 8px;
            background: linear-gradient(90deg, var(--bordo), var(--altin));
        }

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
        
        .btn-add {
            background: linear-gradient(135deg, var(--bordo) 0%, var(--bordo-acik) 100%);
            color: white; border: none; border-radius: 20px;
            padding: 18px; width: 100%; font-weight: 700; transition: 0.4s;
            box-shadow: 0 10px 25px rgba(128, 0, 0, 0.2);
            letter-spacing: 1px;
        }
        .btn-add:hover { 
            background: var(--altin); 
            transform: translateY(-3px); 
            box-shadow: 0 15px 35px rgba(212, 175, 55, 0.3);
            color: white;
        }

        .header-title { font-family: 'Playfair Display', serif; color: var(--bordo); font-weight: 800; font-size: 2.2rem; }
        .header-title span { color: var(--altin); }

        .btn-back {
            color: var(--bordo);
            border: 2px solid var(--krem-koyu);
            border-radius: 50%;
            width: 45px; height: 45px;
            display: flex; align-items: center; justify-content: center;
            transition: 0.3s;
            text-decoration: none;
        }
        .btn-back:hover {
            background: var(--bordo);
            color: white;
            border-color: var(--bordo);
        }

        /* ALERT ÖZELLEŞTİRME */
        .alert-success { background: var(--krem-koyu); color: var(--bordo); border: 1px solid var(--bordo); }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <div class="container py-4">
            <div class="d-flex align-items-center mb-5">
                <a href="yonetim.php" class="btn-back me-3 shadow-sm">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="header-title m-0">Yeni Üye <span>Tanımla</span></h2>
            </div>

            <div class="form-card">
                <?php if($mesaj): ?>
                    <div class="alert alert-<?= $mesaj_turu ?> rounded-4 mb-4 border-0 shadow-sm py-3">
                        <i class="fas fa-info-circle me-2"></i> <?= $mesaj ?>
                    </div>
                <?php endif; ?>

                <div class="text-center mb-5">
                    <div class="mb-3" style="font-size: 3rem; color: var(--altin);">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <p class="text-muted small px-lg-5">Yeni bir şef hesabı oluşturarak mutfak kadronuzu genişletin. Kullanıcılar sisteme bu bilgilerle giriş yapabilirler.</p>
                </div>

                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label">Kullanıcı Adı</label>
                        <input type="text" name="kullanici_adi" class="form-control" placeholder="Şefin adını girin..." required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">E-Posta Adresi</label>
                        <input type="email" name="email" class="form-control" placeholder="chef@sunaslife.com" required>
                    </div>
                    <div class="mb-5">
                        <label class="form-label">Geçici Şifre</label>
                        <input type="password" name="sifre" class="form-control" placeholder="••••••••" required>
                    </div>
                    <button type="submit" name="uye_kaydet" class="btn-add text-uppercase">
                        ÜYEYİ SİSTEME DAHİL ET <i class="fas fa-user-plus ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>