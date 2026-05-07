<?php
session_start();
include 'baglanti.php';
mysqli_set_charset($baglanti, "utf8mb4");

if (isset($_SESSION['kullanici_id'])) {
    header("Location: panel.php");
    exit();
}

$hata = "";
if (isset($_POST['giris_yap'])) {
    $email = mysqli_real_escape_string($baglanti, trim($_POST['email']));
    $girilen_sifre = trim($_POST['sifre']);

    if (!empty($email) && !empty($girilen_sifre)) {
        $sorgu = mysqli_query($baglanti, "SELECT id, kullanici_adi, parola FROM kullanicilar WHERE email = '$email'");
        
        if ($sorgu && mysqli_num_rows($sorgu) > 0) {
            $user = mysqli_fetch_assoc($sorgu);
            
            if (password_verify($girilen_sifre, $user['parola']) || $girilen_sifre === $user['parola']) {
                $_SESSION['kullanici_id'] = $user['id'];
                $_SESSION['kullanici_adi'] = $user['kullanici_adi'];
                header("Location: panel.php");
                exit();
            } else {
                $hata = "Gizli parolanız hatalı, lütfen tekrar deneyin.";
            }
        } else {
            $hata = "Bu e-posta atölyemize kayıtlı değil.";
        }
    } else {
        $hata = "Lütfen şef kimliğinizi tam giriniz.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suna's Life | Atölye Girişi</title>
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
        
        body { margin: 0; padding: 0; display: flex; height: 100vh; font-family: 'Poppins', sans-serif; background-color: var(--krem); overflow: hidden; }

        .split-left {
            flex: 1.2; position: relative; display: flex; flex-direction: column; justify-content: center; padding: 10%;
            background: url('https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?q=80&w=2000') center/cover no-repeat;
        }
  
        .split-left::before {
            content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(74, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.7) 100%);
        }
        .left-content { position: relative; z-index: 1; color: white; animation: slideRight 1s ease-out forwards; }
        @keyframes slideRight { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }

        .brand-text { font-family: 'Playfair Display', serif; font-size: 4.5rem; font-weight: 900; line-height: 1.1; margin-bottom: 20px; }
        .brand-text span { color: var(--altin); font-style: italic; }
        .quote-text { font-size: 1.1rem; font-weight: 200; letter-spacing: 2px; opacity: 0.9; max-width: 400px; line-height: 1.8; }

    
        .split-right {
            flex: 1; display: flex; align-items: center; justify-content: center;
            background: var(--krem); position: relative; padding: 40px;
        }
        
        .login-box { width: 100%; max-width: 420px; animation: slideUp 1s ease-out forwards; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }


        .back-link {
            position: absolute; top: 40px; right: 50px; color: var(--bordo);
            text-decoration: none; font-size: 0.8rem; letter-spacing: 2px; text-transform: uppercase;
            transition: 0.3s; display: flex; align-items: center; gap: 8px; font-weight: 600; opacity: 0.7;
        }
        .back-link:hover { opacity: 1; color: var(--altin); }

        .box-title { font-family: 'Playfair Display', serif; font-size: 2.5rem; color: var(--bordo); margin-bottom: 5px; font-weight: 900; }
        .box-subtitle { color: var(--altin); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 4px; margin-bottom: 40px; font-weight: 700; }

   
        .error-msg {
            background: rgba(128, 0, 0, 0.05); border-left: 3px solid var(--bordo);
            color: var(--bordo); padding: 12px 15px; font-size: 0.85rem; font-weight: 500;
            margin-bottom: 30px; letter-spacing: 1px; border-radius: 4px;
        }

     
        .custom-floating { margin-bottom: 25px; }
        .custom-floating .form-control {
            background-color: white; border: 1px solid rgba(0,0,0,0.1); color: var(--yazi);
            border-radius: 12px; font-family: 'Poppins', sans-serif; font-size: 1rem; font-weight: 500;
            box-shadow: 0 5px 15px rgba(0,0,0,0.02); height: 60px;
        }
        .custom-floating .form-control:focus {
            border-color: var(--altin); box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.15);
        }
        .custom-floating label { color: #888; font-weight: 400; letter-spacing: 1px; font-size: 0.9rem; }
        .custom-floating .form-control:focus ~ label,
        .custom-floating .form-control:not(:placeholder-shown) ~ label {
            color: var(--bordo); font-weight: 700; opacity: 1;
        }

        input:-webkit-autofill { -webkit-box-shadow: 0 0 0 30px white inset !important; -webkit-text-fill-color: var(--yazi) !important; }

  
        .btn-login {
            width: 100%; background: var(--bordo); color: white; border: none;
            padding: 18px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
            font-size: 0.9rem; transition: 0.4s; cursor: pointer; border-radius: 12px; margin-top: 10px;
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 10px 20px rgba(128, 0, 0, 0.15);
        }
        .btn-login:hover { background: var(--bordo-koyu); transform: translateY(-3px); box-shadow: 0 15px 25px rgba(128, 0, 0, 0.25); }
        .btn-login i { background: rgba(255,255,255,0.1); padding: 8px; border-radius: 8px; color: var(--altin); }


        .register-hint { margin-top: 40px; text-align: center; font-size: 0.9rem; color: #666; font-weight: 400; }
        .register-hint a { color: var(--bordo); text-decoration: none; font-weight: 700; margin-left: 5px; border-bottom: 2px solid var(--altin); transition: 0.3s; padding-bottom: 2px; }
        .register-hint a:hover { color: var(--altin); border-color: var(--bordo); }

    
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
            <div class="brand-text">Gastro <br><span>Sanat.</span></div>
            <div class="quote-text">Yapay zeka asistanınızla sınırları aşın. Mutfaktaki yerinizi alın ve yeni nesil tariflerinizi ustalıkla sergileyin.</div>
        </div>
    </div>

  
    <div class="split-right">
        <a href="index.php" class="back-link">Atölyeye Dön <i class="fas fa-arrow-right ms-2"></i></a>
        
        <div class="login-box">
            <h2 class="box-title">Hoş Geldiniz</h2>
            <div class="box-subtitle">Şef Giriş Paneli</div>

            <?php if($hata): ?>
                <div class="error-msg"><i class="fas fa-exclamation-triangle me-2"></i> <?= $hata ?></div>
            <?php endif; ?>

            <form method="POST">
                
               
                <div class="form-floating custom-floating">
                    <input type="email" class="form-control" id="emailInput" name="email" placeholder="name@example.com" required>
                    <label for="emailInput">E-Posta Adresiniz</label>
                </div>

                <div class="form-floating custom-floating">
                    <input type="password" class="form-control" id="sifreInput" name="sifre" placeholder="Password" required>
                    <label for="sifreInput">Gizli Parolanız</label>
                </div>

                <button type="submit" name="giris_yap" class="btn-login">
                    İçeri Gir <i class="fas fa-lock-open"></i>
                </button>
            </form>

            <div class="register-hint">
                Kendi imzanı yaratmak ister misin? <a href="kayit.php">Hemen Katıl</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>