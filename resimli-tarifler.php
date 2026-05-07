<?php
session_start();
include 'baglanti.php';

if (!isset($_SESSION['kullanici_id'])) {
    header("Location: giris.php");
    exit();
}


$arama = isset($_GET['ara']) ? mysqli_real_escape_string($baglanti, $_GET['ara']) : '';
$kat_filtre = isset($_GET['kategori']) ? mysqli_real_escape_string($baglanti, $_GET['kategori']) : '';


$sorgu_metni = "SELECT t.*, k.kullanici_adi FROM tarifler t 
                JOIN kullanicilar k ON t.ekleyen_id = k.id 
                WHERE (t.video_url = '' OR t.video_url IS NULL)";


if (!empty($arama)) {
    $sorgu_metni .= " AND (t.baslik LIKE '%$arama%' OR t.malzemeler LIKE '%$arama%')";
}


if (!empty($kat_filtre)) {
    $sorgu_metni .= " AND t.kategori = '$kat_filtre'";
}

$sorgu_metni .= " ORDER BY t.id DESC";
$tarifler = mysqli_query($baglanti, $sorgu_metni);


$kategoriler_sorgu = mysqli_query($baglanti, "SELECT DISTINCT kategori FROM tarifler WHERE kategori != ''");
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pratik Tarifler | Suna's Life</title>
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

        body { background-color: var(--arkaplan); font-family: 'Poppins', sans-serif; margin: 0; color: var(--yazi); }
        .main-content { padding: 40px; margin-left: 280px; min-height: 100vh; }

      
        .search-panel {
            background: white;
            padding: 25px;
            border-radius: 25px;
            box-shadow: 0 10px 30px rgba(128, 0, 0, 0.03);
            margin-bottom: 35px;
            border: 1px solid var(--krem-koyu);
        }

        .form-control, .form-select {
            border-radius: 12px;
            padding: 12px 15px;
            border: 2px solid var(--krem-koyu);
            background-color: var(--arkaplan);
            color: var(--yazi);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--bordo);
            box-shadow: 0 0 0 0.25rem rgba(128, 0, 0, 0.05);
            background-color: white;
        }

        .btn-filtre {
            background: linear-gradient(135deg, var(--bordo) 0%, var(--bordo-acik) 100%);
            color: white;
            border-radius: 12px;
            padding: 12px 25px;
            font-weight: 600;
            transition: 0.3s;
            border: none;
            box-shadow: 0 5px 15px rgba(128, 0, 0, 0.2);
        }

        .btn-filtre:hover {
            background: var(--altin);
            color: var(--bordo);
            transform: translateY(-2px);
        }

        .recipe-list-item {
            background: white;
            border-radius: 20px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            padding: 25px 35px;
            transition: 0.3s ease;
            border: 1px solid var(--krem-koyu);
            text-decoration: none !important;
        }

        .recipe-list-item:hover {
            transform: translateX(12px);
            box-shadow: 0 10px 30px rgba(128, 0, 0, 0.08);
            border-color: var(--bordo);
        }

        .list-info { flex-grow: 1; }

        .badge-bordo { 
            background: var(--krem-koyu); color: var(--bordo); font-weight: 700; 
            font-size: 0.7rem; padding: 5px 12px; border-radius: 8px; 
            margin-bottom: 8px; display: inline-block; letter-spacing: 1px;
            border: 1px solid rgba(128, 0, 0, 0.1);
        }

        .list-title { 
            color: var(--bordo); 
            font-weight: 700; 
            font-size: 1.4rem; 
            margin: 0 0 5px 0; 
        }

        .list-meta { 
            font-size: 0.85rem; 
            color: #5a4a42; 
            display: flex; 
            gap: 25px; 
        }

        .list-meta b { color: var(--bordo); }
        .list-meta i { color: var(--altin); margin-right: 5px; }

        .list-arrow {
            color: var(--krem-koyu);
            font-size: 1.4rem;
            transition: 0.3s;
            margin-left: 20px;
        }

        .recipe-list-item:hover .list-arrow {
            color: var(--bordo);
            transform: translateX(5px);
        }

        .header-title { color: var(--bordo); font-weight: 800; font-size: 2.2rem; }
        .header-title span { color: var(--altin); }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <h2 class="header-title mb-4">
            <i class="fas fa-list-ul me-3" style="color:var(--altin)"></i>Pratik <span>Tarifler</span>
        </h2>

        <div class="search-panel">
            <form method="GET" class="row g-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0" style="border-radius: 12px 0 0 12px;"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="ara" class="form-control border-0" placeholder="Tarif veya malzeme ara..." value="<?= htmlspecialchars($arama) ?>" style="border-radius: 0 12px 12px 0;">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="kategori" class="form-select border-0">
                        <option value="">Tüm Kategoriler</option>
                        <?php while($kat = mysqli_fetch_assoc($kategoriler_sorgu)): ?>
                            <option value="<?= $kat['kategori'] ?>" <?= ($kat_filtre == $kat['kategori']) ? 'selected' : '' ?>>
                                <?= $kat['kategori'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-filtre w-100">Filtrele</button>
                        <a href="resimli-tarifler.php" class="btn btn-light d-flex align-items-center justify-content-center" style="border-radius: 12px; width: 50px; border: 2px solid var(--krem-koyu);">
                            <i class="fas fa-sync-alt text-muted"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="container-fluid p-0">
            <?php if(mysqli_num_rows($tarifler) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($tarifler)): ?>
                    
                    <a href="tarif-detay.php?id=<?= $row['id'] ?>" class="recipe-list-item">
                        <div class="list-info">
                            <span class="badge-bordo text-uppercase"><?= htmlspecialchars($row['kategori']) ?></span>
                            
                            <h4 class="list-title"><?= htmlspecialchars($row['baslik']) ?></h4>
                            
                            <div class="list-meta">
                                <span><i class="fas fa-utensils"></i> Şef: <b><?= htmlspecialchars($row['kullanici_adi']) ?></b></span>
                                <span><i class="far fa-clock"></i> 15-20 Dakika</span>
                                <span class="d-none d-sm-inline"><i class="fas fa-check-circle"></i> Resimli Anlatım</span>
                            </div>
                        </div>

                        <div class="list-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>

                <?php endwhile; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x mb-3" style="color: var(--bordo); opacity: 0.2;"></i>
                    <h5 class="text-muted">Aramanıza uygun tarif bulunamadı.</h5>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>