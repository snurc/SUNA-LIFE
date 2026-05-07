<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suna's Life | Tekrar Görüşmek Üzere</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bordo: #800000;
            --bordo-acik: #A52A2A;
            --altin: #D4AF37;
            --yazi: #2D1B1B;
            --krem: #FFFBF2;
            --krem-koyu: #F5E6D3;
        }
        body {
            background: var(--krem);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            overflow: hidden;
        }
        
      
        body::before {
            content: "";
            position: absolute;
            width: 400px; height: 400px;
            background: var(--bordo);
            opacity: 0.03;
            border-radius: 50%;
            top: -150px; right: -150px;
        }
        body::after {
            content: "";
            position: absolute;
            width: 300px; height: 300px;
            background: var(--altin);
            opacity: 0.05;
            border-radius: 50%;
            bottom: -100px; left: -100px;
        }

        .logout-card {
            background: white;
            padding: 70px 50px;
            border-radius: 50px;
            box-shadow: 0 30px 70px rgba(128, 0, 0, 0.05);
            text-align: center;
            max-width: 480px;
            width: 90%;
            position: relative;
            z-index: 1;
            border: 1px solid var(--krem-koyu);
        }
        
        .icon-box {
            width: 90px; height: 90px;
            background: var(--krem);
            color: var(--bordo);
            border-radius: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            font-size: 2.8rem;
            transform: rotate(-12deg);
            box-shadow: 0 10px 20px rgba(128, 0, 0, 0.1);
            border: 1px solid var(--altin);
        }

        h1 {
            font-family: 'Playfair Display', serif;
            color: var(--bordo);
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 20px;
        }
        h1 span { color: var(--altin); }
        
        p {
            color: var(--yazi);
            opacity: 0.8;
            line-height: 1.8;
            margin-bottom: 40px;
            font-size: 0.95rem;
        }

        .btn-group-custom {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .btn-custom {
            padding: 16px;
            border-radius: 20px;
            font-weight: 700;
            text-decoration: none;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            letter-spacing: 1px;
        }

        .btn-relogin {
            background: var(--bordo);
            color: white;
            box-shadow: 0 10px 25px rgba(128, 0, 0, 0.2);
        }
        .btn-relogin:hover {
            background: var(--altin);
            color: var(--bordo);
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(212, 175, 55, 0.3);
        }

        .btn-home {
            background: var(--krem);
            color: var(--bordo);
            border: 1px solid var(--krem-koyu);
        }
        .btn-home:hover {
            background: white;
            color: var(--altin);
            border-color: var(--altin);
            transform: translateY(-3px);
        }

        .footer-note {
            margin-top: 40px;
            font-size: 0.7rem;
            color: var(--bordo);
            opacity: 0.4;
            letter-spacing: 3px;
            font-weight: 700;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

    <div class="logout-card">
        <div class="icon-box">
            <i class="fas fa-utensils"></i>
        </div>
        <h1>Suna's <span>Life</span></h1>
        <p>Ellerine sağlık şefim! Bugünün lezzet dolu paylaşımı için teşekkürler. Mutfak, yeni tariflerin için her zaman hazır bekliyor olacak.</p>
        
        <div class="btn-group-custom">
            <a href="giris.php" class="btn-custom btn-relogin">
                <i class="fas fa-sign-in-alt me-2"></i> TEKRAR GİRİŞ YAP
            </a>
            <a href="index.php" class="btn-custom btn-home">
                <i class="fas fa-home me-2"></i> ANA SAYFAYA DÖN
            </a>
        </div>

        <div class="footer-note">
            Lezzet ve Sanatla Kalın...
        </div>
    </div>

</body>
</html>