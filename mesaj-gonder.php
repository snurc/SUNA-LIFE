<?php
session_start();
include 'baglanti.php';


if (!isset($_SESSION['kullanici_id'])) {
    header("Location: giris.php");
    exit();
}

$mesaj_durum = "";


if (isset($_POST['mesaj_at'])) {
    $gonderen = $_SESSION['kullanici_id'];
    $alici = 1; // Baş Şef (Siz)
    $icerik = mysqli_real_escape_string($baglanti, $_POST['mesaj_icerik']);

    if (!empty($icerik)) {
        $ekle = "INSERT INTO mesajlar (gonderen_id, alici_id, mesaj_icerik) VALUES ('$gonderen', '$alici', '$icerik')";
        if (mysqli_query($baglanti, $ekle)) {
            $mesaj_durum = "basarili";
        } else {
            $mesaj_durum = "hata";
        }
    } else {
        $mesaj_durum = "bos";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şef'e Soru Sor | Suna's Life</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { 
            --bordo: #800000; 
            --bordo-acik: #A52A2A;
            --altin: #D4AF37;
            --arkaplan: #FFFBF2;
            --yazi: #2D1B1B;
            --krem-koyu: #F5E6D3;
        }

        body { background-color: var(--arkaplan); font-family: 'Poppins', sans-serif; margin: 0; display: flex; color: var(--yazi); }
        .main-content { flex-grow: 1; padding: 40px; margin-left: 280px; min-height: 100vh; }

        .message-card-premium {
            background: white; border-radius: 40px; padding: 50px;
            box-shadow: 0 20px 60px rgba(128, 0, 0, 0.05); border: 1px solid var(--krem-koyu);
            position: relative; overflow: hidden;
        }
        .message-card-premium::before {
            content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 8px;
            background: linear-gradient(90deg, var(--bordo), var(--altin));
        }

        .custom-textarea {
            border-radius: 25px; border: 2px solid var(--krem-koyu); padding: 25px;
            transition: all 0.3s ease; background-color: #ffffff; font-size: 1rem;
            resize: none; color: var(--yazi);
        }
        .custom-textarea:focus {
            border-color: var(--bordo); box-shadow: 0 10px 25px rgba(128, 0, 0, 0.05);
            background-color: white; outline: none;
        }

        .btn-send-chef {
            background: linear-gradient(135deg, var(--bordo) 0%, var(--bordo-acik) 100%);
            color: white; border: none; border-radius: 20px; padding: 18px;
            font-weight: 700; width: 100%; transition: 0.4s; letter-spacing: 1.5px;
            box-shadow: 0 10px 25px rgba(128, 0, 0, 0.2);
        }
        .btn-send-chef:hover {
            background: var(--altin); transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(212, 175, 55, 0.3); color: white;
        }


        .status-alert {
            border-radius: 20px; border: none; padding: 15px 25px; font-weight: 600;
            animation: fadeInDown 0.5s ease;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header-title { color: var(--bordo); font-weight: 800; font-size: 2.2rem; margin-bottom: 40px; }
        .header-title span { color: var(--altin); }
        
        .icon-box-bg {
            width:70px; height:70px; background: var(--krem-koyu); color: var(--bordo); 
            border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.8rem;
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <div class="container py-4">
            <h2 class="header-title">
                <i class="fas fa-paper-plane me-3" style="color:var(--altin)"></i>Şef'e <span>Soru Sor</span>
            </h2>

            <div class="message-card-premium mx-auto" style="max-width: 700px;">
                
                <?php if($mesaj_durum == "basarili"): ?>
                    <div class="alert status-alert mb-4" style="background: var(--krem-koyu); color: var(--bordo);">
                        <i class="fas fa-check-circle me-2"></i> Mesajınız Suna Şef'e başarıyla uçtu! En kısa sürede yanıtlanacak.
                    </div>
                <?php elseif($mesaj_durum == "hata"): ?>
                    <div class="alert status-alert mb-4" style="background:#FFF0F0; color: var(--bordo);">
                        <i class="fas fa-exclamation-circle me-2"></i> Mutfakta bir bağlantı sorunu oldu, lütfen tekrar deneyin.
                    </div>
                <?php endif; ?>

                <div class="text-center mb-5">
                    <div class="icon-box-bg mb-3 mx-auto">
                        <i class="fas fa-comment-dots"></i>
                    </div>
                    <p class="text-muted">Bir tarif hakkında sorunuz mu var? Yoksa sadece teşekkür etmek mi istiyorsunuz? Suna Şef sizi dinliyor.</p>
                </div>

                <form method="POST">
                    <div class="mb-5">
                        <label class="form-label fw-bold text-uppercase small px-3" style="color:var(--bordo); letter-spacing:1px;">Mesajınızın Detayı</label>
                        <textarea name="mesaj_icerik" class="form-control custom-textarea" rows="7" placeholder="Mutfak sırları hakkında merak ettiğiniz her şeyi yazabilirsiniz..." required></textarea>
                    </div>

                    <button type="submit" name="mesaj_at" class="btn-send-chef">
                        MESAJI ŞEF'E İLET <i class="fas fa-magic ms-2"></i>
                    </button>
                </form>
            </div>
            
            <div class="text-center mt-5">
                <p class="small text-muted"><i class="fas fa-shield-alt me-1"></i> Mesajınız şifreli olarak doğrudan Suna Şef'in paneline iletilir.</p>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>