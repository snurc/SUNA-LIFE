<?php
session_start();
include 'baglanti.php';

if (!isset($_SESSION['kullanici_id'])) {
    header("Location: giris.php");
    exit();
}

$kullanici_id = $_SESSION['kullanici_id'];
$mesaj = "";


$sorgu = mysqli_query($baglanti, "SELECT * FROM kullanicilar WHERE id = '$kullanici_id'");
$user = mysqli_fetch_assoc($sorgu);


if (isset($_POST['guncelle'])) {
    $yeni_ad = mysqli_real_escape_string($baglanti, $_POST['kullanici_adi']);
    $yeni_sifre = $_POST['yeni_sifre'];

    if (!empty($yeni_sifre)) {
     
        $hashed_password = password_hash($yeni_sifre, PASSWORD_DEFAULT);
        $guncelle_sorgu = "UPDATE kullanicilar SET kullanici_adi = '$yeni_ad', parola = '$hashed_password' WHERE id = '$kullanici_id'";
    } else {
      
        $guncelle_sorgu = "UPDATE kullanicilar SET kullanici_adi = '$yeni_ad' WHERE id = '$kullanici_id'";
    }

    if (mysqli_query($baglanti, $guncelle_sorgu)) {
        $_SESSION['kullanici_adi'] = $yeni_ad; // 
        $mesaj = "<div class='alert alert-bordo'>Profil başarıyla güncellendi şefim!</div>";
        header("Refresh: 2; url=profil.php");
    } else {
        $mesaj = "<div class='alert alert-danger'>Mutfakta bir hata oluştu!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suna's Life | Profilim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
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

        .header-title { color: var(--bordo); font-weight: 800; font-size: 2.2rem; margin-bottom: 40px; }
        .header-title span { color: var(--altin); }


        .profile-card {
            background: white;
            border: 1px solid var(--krem-koyu);
            border-radius: 40px;
            padding: 50px;
            max-width: 650px;
            box-shadow: 0 20px 60px rgba(128, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }
        .profile-card::before {
            content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 8px;
            background: linear-gradient(90deg, var(--bordo), var(--altin));
        }

        .form-label { color: var(--bordo); font-weight: 700; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; }
        
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

        .avatar-box {
            width: 100px; height: 100px;
            background: linear-gradient(135deg, var(--bordo) 0%, var(--bordo-acik) 100%);
            color: white;
            border-radius: 30px;
            display: inline-flex; align-items: center; justify-content: center;
            margin-bottom: 20px;
            box-shadow: 0 10px 25px rgba(128, 0, 0, 0.2);
            transform: rotate(-5deg);
        }

        .btn-update {
            background: linear-gradient(135deg, var(--bordo) 0%, var(--bordo-acik) 100%);
            color: white;
            border: none;
            border-radius: 18px;
            padding: 16px;
            font-weight: 700;
            letter-spacing: 1px;
            transition: 0.4s;
            box-shadow: 0 15px 30px rgba(128, 0, 0, 0.2);
        }
        .btn-update:hover {
            background: var(--altin);
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(212, 175, 55, 0.3);
            color: white;
        }

        .alert-bordo {
            background: var(--krem-koyu);
            color: var(--bordo);
            border: 1px solid var(--bordo);
            border-radius: 15px;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <h2 class="header-title" style="font-family:'Playfair Display', serif;">Profil <span>Ayarları</span></h2>
        
        <div class="container-fluid p-0">
            <?php echo $mesaj; ?>

            <div class="profile-card shadow-sm">
                <form method="POST">
                    <div class="text-center mb-5">
                        <div class="avatar-box">
                            <i class="fas fa-user-shield fa-3x"></i>
                        </div>
                        <h4 class="fw-bold" style="color: var(--bordo);"><?= htmlspecialchars($user['email']) ?></h4>
                        <p class="text-muted small">E-posta adresiniz şef kimliğinizdir ve değiştirilemez.</p>
                    </div>

                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label">Şef Adınız</label>
                            <input type="text" name="kullanici_adi" class="form-control" value="<?= htmlspecialchars($user['kullanici_adi']) ?>" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Yeni Şifre (Değiştirmek istemiyorsanız boş bırakın)</label>
                            <input type="password" name="yeni_sifre" class="form-control" placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit" name="guncelle" class="btn btn-update w-100 mt-5 py-3 fw-bold">
                        DEĞİŞİKLİKLERİ KAYDET <i class="fas fa-save ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>