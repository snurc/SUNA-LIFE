<?php
session_start();
include 'baglanti.php';
mysqli_set_charset($baglanti, "utf8mb4");

if (isset($_SESSION['kullanici_id'])) {
    header("Location: panel.php");
    exit();
}

$hata = "";
$basari = "";

if (isset($_POST['kayit_ol'])) {
    $kullanici_adi = mysqli_real_escape_string($baglanti, trim($_POST['kullanici_adi']));
    $email = mysqli_real_escape_string($baglanti, trim($_POST['email']));
    $sifre = trim($_POST['sifre']);
    $sifre_tekrar = trim($_POST['sifre_tekrar']);

    if (!empty($kullanici_adi) && !empty($email) && !empty($sifre) && !empty($sifre_tekrar)) {
        if ($sifre === $sifre_tekrar) {
            $kontrol_sorgu = mysqli_query($baglanti, "SELECT id FROM kullanicilar WHERE email = '$email'");
            
            if (mysqli_num_rows($kontrol_sorgu) > 0) {
                $hata = "Bu e-posta adresi ile zaten bir şef önlüğü alınmış.";
            } else {
                $sifre_hash = password_hash($sifre, PASSWORD_DEFAULT);
                $ekle_sorgu = mysqli_query($baglanti, "INSERT INTO kullanicilar (kullanici_adi, email, parola) VALUES ('$kullanici_adi', '$email', '$sifre_hash')");
                
                if ($ekle_sorgu) {
                    $basari = "Kayıt işlemi başarıyla tamamlandı. Giriş yapabilirsiniz.";
                } else {
                    $hata = "Kayıt sırasında sistemsel bir hata oluştu.";
                }
            }
        } else {
            $hata = "Girdiğiniz parolalar birbiriyle uyuşmuyor.";
        }
    } else {
        $hata = "Lütfen tüm başvuru alanlarını eksiksiz doldurun.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suna's Life | Kayıt Ol</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,700&family=Poppins:wght@200;300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { 
            --bordo: #800000;       
            --bordo-koyu: #4A0000; 
            --altin: #D4AF37;       
            --krem: #FFFDF9;        
            --yazi: #2D1B1B;        
        }
        
        body { margin: 0; padding: 0; display: flex; height: 100vh; font-family: 'Poppins', sans-serif; background-color: var(--krem); overflow-x: hidden; }

        .split-left {
            flex: 1; position: relative; display: flex; flex-direction: column; justify-content: center; padding: 10%;
            background: url('https://images.unsplash.com/photo-1577106263724-2c8e03bfe9bc?q=80&w=2000') center/cover no-repeat;
        }
        .split-left::before {
            content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(74, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0.6) 100%);
        }
        .left-content { position: relative; z-index: 1; color: white; animation: slideRight 1s ease-out forwards; }
        @keyframes slideRight { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }

        .brand-text { font-family: 'Playfair Display', serif; font-size: 4.5rem; font-weight: 900; line-height: 1.1; margin-bottom: 20px; }
        .brand-text span { color: var(--altin); font-style: italic; }
        .quote-text { font-size: 1.1rem; font-weight: 200; letter-spacing: 2px; opacity: 0.9; max-width: 400px; line-height: 1.8; }

        .split-right {
            flex: 1.3; display: flex; align-items: center; justify-content: center;
            background: var(--krem); position: relative; padding: 40px; overflow-y: auto;
        }
        
        .register-box { width: 100%; max-width: 550px; animation: slideUp 1s ease-out forwards; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

        .back-link {
            position: absolute; top: 40px; right: 50px; color: var(--bordo);
            text-decoration: none; font-size: 0.8rem; letter-spacing: 2px; text-transform: uppercase;
            transition: 0.3s; display: flex; align-items: center; gap: 8px; font-weight: 600; opacity: 0.7;
        }
        .back-link:hover { opacity: 1; color: var(--altin); }

        .box-title { font-family: 'Playfair Display', serif; font-size: 2.5rem; color: var(--bordo); margin-bottom: 5px; font-weight: 900; }
        .box-subtitle { color: var(--altin); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 4px; margin-bottom: 30px; font-weight: 700; }

        .msg-box { padding: 12px 15px; font-size: 0.85rem; font-weight: 500; margin-bottom: 25px; letter-spacing: 1px; border-radius: 4px; border-left: 3px solid; }
        .msg-error { background: rgba(128, 0, 0, 0.05); border-color: var(--bordo); color: var(--bordo); }
        .msg-success { background: rgba(212, 175, 55, 0.1); border-color: var(--altin); color: #1e4a28; }

        .custom-floating .form-control {
            background-color: white; border: 1px solid rgba(0,0,0,0.1); color: var(--yazi);
            border-radius: 12px; font-family: 'Poppins', sans-serif; font-size: 0.95rem; font-weight: 500;
            box-shadow: 0 5px 15px rgba(0,0,0,0.02); height: 55px;
        }
        .custom-floating .form-control:focus { border-color: var(--altin); box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.15); }
        .custom-floating label { color: #888; font-weight: 400; letter-spacing: 1px; font-size: 0.85rem; }
        .custom-floating .form-control:focus ~ label, .custom-floating .form-control:not(:placeholder-shown) ~ label { color: var(--bordo); font-weight: 700; opacity: 1; }

        input:-webkit-autofill { -webkit-box-shadow: 0 0 0 30px white inset !important; -webkit-text-fill-color: var(--yazi) !important; }

        .terms-group { display: flex; align-items: flex-start; gap: 15px; margin-bottom: 30px; margin-top: 10px; }
        .custom-checkbox {
            appearance: none; background-color: white; margin: 0; font: inherit; color: currentColor;
            width: 1.2rem; height: 1.2rem; border: 2px solid rgba(0,0,0,0.2); display: grid; place-content: center;
            cursor: pointer; transition: 0.2s; border-radius: 4px; margin-top: 2px;
        }
        .custom-checkbox::before {
            content: ""; width: 0.65em; height: 0.65em; transform: scale(0);
            transition: 120ms transform ease-in-out; box-shadow: inset 1em 1em white;
            transform-origin: center; clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
        }
        .custom-checkbox:checked { border-color: var(--bordo); background-color: var(--bordo); }
        .custom-checkbox:checked::before { transform: scale(1); }
        
        .terms-text { font-size: 0.8rem; color: #666; font-weight: 400; line-height: 1.6; }
        .terms-text a { color: var(--bordo); text-decoration: none; border-bottom: 1px solid var(--altin); font-weight: 600; transition: 0.3s; }
        .terms-text a:hover { color: var(--altin); border-color: var(--bordo); }

        .btn-register {
            width: 100%; background: var(--bordo); color: white; border: none;
            padding: 16px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
            font-size: 0.9rem; transition: 0.4s; cursor: pointer; border-radius: 12px;
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 10px 20px rgba(128, 0, 0, 0.15);
        }
        .btn-register:hover { background: var(--bordo-koyu); transform: translateY(-3px); box-shadow: 0 15px 25px rgba(128, 0, 0, 0.25); }
        .btn-register i { background: rgba(255,255,255,0.1); padding: 8px; border-radius: 8px; color: var(--altin); }

        .login-hint { margin-top: 30px; text-align: center; font-size: 0.9rem; color: #666; font-weight: 400; }
        .login-hint a { color: var(--bordo); text-decoration: none; font-weight: 700; margin-left: 5px; border-bottom: 2px solid var(--altin); transition: 0.3s; padding-bottom: 2px; }
        .login-hint a:hover { color: var(--altin); border-color: var(--bordo); }

        @media (max-width: 992px) {
            body { flex-direction: column; overflow: auto; }
            .split-left { flex: none; min-height: 40vh; padding: 40px; text-align: center; align-items: center; }
            .quote-text { display: none; }
            .split-right { flex: none; min-height: 60vh; padding: 40px 20px; }
            .back-link { top: 20px; right: 20px; }
        }
    </style>
</head>
<body>

    <div class="split-left">
        <div class="left-content">
            <div class="brand-text">Yeni Bir <br><span>Başlangıç.</span></div>
            <div class="quote-text">Atölyeye katılın. Reçetelerinizi ölümsüzleştirin ve seçkin gastronomi ağının bir parçası olun.</div>
        </div>
    </div>

    <div class="split-right">
        <a href="index.php" class="back-link">Ana Sayfa <i class="fas fa-arrow-right ms-2"></i></a>
        
        <div class="register-box">
            <h2 class="box-title">Kayıt Ol</h2>
            <div class="box-subtitle">Hesap Oluştur</div>

            <?php if($hata): ?>
                <div class="msg-box msg-error"><i class="fas fa-exclamation-triangle me-2"></i> <?= $hata ?></div>
            <?php endif; ?>
            
            <?php if($basari): ?>
                <div class="msg-box msg-success">
                    <i class="fas fa-check-circle me-2"></i> <?= $basari ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="form-floating custom-floating">
                            <!-- İsim olarak değiştirildi -->
                            <input type="text" class="form-control" id="isimInput" name="kullanici_adi" placeholder="İsim" required autocomplete="off">
                            <label for="isimInput">İsim</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating custom-floating">
                            <!-- E-Posta olarak değiştirildi -->
                            <input type="email" class="form-control" id="emailInput" name="email" placeholder="E-Posta" required autocomplete="off">
                            <label for="emailInput">E-Posta</label>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-2">
                    <div class="col-md-6">
                        <div class="form-floating custom-floating">
                            <!-- Parola olarak değiştirildi -->
                            <input type="password" class="form-control" id="sifreInput" name="sifre" placeholder="Parola" required>
                            <label for="sifreInput">Parola</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating custom-floating">
                            <!-- Parola Tekrar olarak değiştirildi -->
                            <input type="password" class="form-control" id="sifreTekrarInput" name="sifre_tekrar" placeholder="Parola Tekrar" required>
                            <label for="sifreTekrarInput">Parola Tekrar</label>
                        </div>
                    </div>
                </div>

                <div class="terms-group">
                    <input type="checkbox" id="terms" class="custom-checkbox" required>
                    <label for="terms" class="terms-text">
                        Kullanım <a href="#">koşullarını</a> okudum ve kabul ediyorum.
                    </label>
                </div>

                <button type="submit" name="kayit_ol" class="btn-register">
                    Kayıt Ol <i class="fas fa-user-plus"></i>
                </button>
            </form>

            <div class="login-hint">
                Zaten bir hesabın var mı? <a href="giris.php">Giriş Yap</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>