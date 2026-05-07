<?php
session_start();
include 'baglanti.php';

if (!isset($_SESSION['kullanici_id'])) {
    header("Location: giris.php");
    exit();
}

$kullanici_id = $_SESSION['kullanici_id'];
$mesaj = "";

if (isset($_POST['tarif_kaydet'])) {
    $baslik = mysqli_real_escape_string($baglanti, $_POST['baslik']);
    $v_url = mysqli_real_escape_string($baglanti, $_POST['video_url']);
    $kat = mysqli_real_escape_string($baglanti, $_POST['kategori']);
    $malzeme = mysqli_real_escape_string($baglanti, $_POST['malzemeler']);
    $yapilis = mysqli_real_escape_string($baglanti, $_POST['yapilis']);
    

    $onay_durumu = ($kullanici_id == 1) ? 1 : 0;
    
    $resim_adi = "";
    if (!empty($_FILES['tarif_resim']['name'])) {
        $resim_adi = time() . "_" . $_FILES['tarif_resim']['name'];
        move_uploaded_file($_FILES['tarif_resim']['tmp_name'], "resimler/" . $resim_adi);
    }

    if (!empty($baslik)) {
      
        $sorgu = "INSERT INTO tarifler (baslik, video_url, tarif_resim, kategori, malzemeler, yapilis, ekleyen_id, onay_durumu) 
                  VALUES ('$baslik', '$v_url', '$resim_adi', '$kat', '$malzeme', '$yapilis', '$kullanici_id', '$onay_durumu')";
        
        if (mysqli_query($baglanti, $sorgu)) {
            if ($onay_durumu == 1) {
                $mesaj = "<div class='alert border-0 rounded-4 shadow-sm py-3 mb-4' style='background:var(--krem-koyu); color:var(--bordo);'><i class='fas fa-check-circle me-2'></i> ✨ Tarifin mutfağa başarıyla eklendi ve yayınlandı şefim!</div>";
            } else {
                $mesaj = "<div class='alert border-0 rounded-4 shadow-sm py-3 mb-4' style='background:#e1f5fe; color:#01579b;'><i class='fas fa-clock me-2'></i> ✨ Tarifin mutfağa iletildi. Admin onayından sonra yayına alınacaktır şefim!</div>";
            }
        } else {
            $mesaj = "<div class='alert alert-danger'>Hata: " . mysqli_error($baglanti) . "</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Bir Lezzet Paylaş | Suna's Life</title>
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

        .form-card-premium {
            background: white; border-radius: 40px; padding: 50px;
            box-shadow: 0 20px 50px rgba(128, 0, 0, 0.03); border: 1px solid var(--krem-koyu);
            position: relative; overflow: hidden;
        }
        .form-card-premium::before {
            content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 8px;
            background: linear-gradient(90deg, var(--bordo), var(--altin));
        }

        .form-label { color: var(--bordo); font-weight: 700; font-size: 0.85rem; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        
        .custom-input {
            border-radius: 18px; border: 2px solid var(--krem-koyu); padding: 15px 20px;
            transition: all 0.3s ease; background-color: var(--arkaplan); color: var(--yazi); font-size: 0.95rem;
        }
        .custom-input:focus {
            border-color: var(--bordo); box-shadow: 0 0 0 4px rgba(128, 0, 0, 0.05); background-color: white; outline: none;
        }
        .custom-input::placeholder { color: #b2bec3; font-weight: 300; }

        .btn-chef-save {
            background: linear-gradient(135deg, var(--bordo) 0%, var(--bordo-acik) 100%);
            color: white; border-radius: 20px; padding: 18px; font-weight: 700;
            border: none; transition: 0.4s; letter-spacing: 1.5px;
            box-shadow: 0 10px 25px rgba(128, 0, 0, 0.2);
        }
        .btn-chef-save:hover {
            transform: translateY(-5px); box-shadow: 0 15px 35px rgba(212, 175, 55, 0.3);
            background: var(--altin); color: white;
        }

        .custom-file-input::-webkit-file-upload-button {
            background: var(--bordo); color: white; border: none; border-radius: 10px; padding: 5px 15px; margin-right: 10px; cursor: pointer;
        }

        .header-title { font-family: 'Playfair Display', serif; font-weight: 800; color: var(--bordo); font-size: 2.5rem; }
        .header-title span { color: var(--altin); }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <div class="container-fluid py-4">
            <div class="form-card-premium mx-auto" style="max-width: 900px;">
                <div class="text-center mb-5">
                    <h2 class="header-title">Yeni Bir <span>Sanat Paylaş</span></h2>
                    <p class="text-muted fw-bold small text-uppercase" style="letter-spacing: 2px;">Tarifindeki her detay, bir başkasının sofrasındaki mutluluk olacak.</p>
                </div>

                <?= $mesaj ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label"><i class="fas fa-signature me-2 text-warning"></i>Tarif Başlığı</label>
                            <input type="text" name="baslik" class="form-control custom-input" placeholder="Örn: Bordo Soslu Bonfile" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fas fa-list me-2 text-warning"></i>Kategori</label>
                            <select name="kategori" class="form-select custom-input">
                                <option>Tatlılar</option>
                                <option>Ana Yemekler</option>
                                <option>Hamur İşleri</option>
                                <option>Çorbalar</option>
                                <option value="İçecekler">İçecekler</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fab fa-youtube me-2 text-warning"></i>YouTube Hazırlık Linki</label>
                            <input type="url" name="video_url" class="form-control custom-input" placeholder="https://youtube.com/...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fas fa-camera-retro me-2 text-warning"></i>Vitrinin İçin Fotoğraf</label>
                            <input type="file" name="tarif_resim" class="form-control custom-input custom-file-input">
                        </div>
                        <div class="col-12">
                            <label class="form-label"><i class="fas fa-mortar-pestle me-2 text-warning"></i>Malzeme Listesi</label>
                            <textarea name="malzemeler" class="form-control custom-input" rows="3" placeholder="Her satıra bir malzeme (Örn: 2 adet yumurta)..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label"><i class="fas fa-utensils me-2 text-warning"></i>Adım Adım Hazırlanışı</label>
                            <textarea name="yapilis" class="form-control custom-input" rows="6" placeholder="Önce ocağı kısın, sonra sevgini katın..."></textarea>
                        </div>
                        <div class="col-12 mt-5">
                            <button type="submit" name="tarif_kaydet" class="btn btn-chef-save w-100 text-uppercase">
                                <i class="fas fa-magic me-2"></i> TARİFİ DÜNYAYA DUYUR
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>