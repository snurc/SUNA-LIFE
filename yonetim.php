<?php
session_start();
include 'baglanti.php';


if (!isset($_SESSION['kullanici_id']) || $_SESSION['kullanici_id'] != 1) {
    header("Location: panel.php"); 
    exit();
}


$bekleyenler_sorgu = "SELECT t.*, k.kullanici_adi FROM tarifler t 
                      JOIN kullanicilar k ON t.ekleyen_id = k.id 
                      WHERE t.onay_durumu = 0 ORDER BY t.id DESC";
$bekleyenler = mysqli_query($baglanti, $bekleyenler_sorgu);
$bekleyen_sayisi = mysqli_num_rows($bekleyenler);


$tarifler_sorgu = "SELECT t.*, k.kullanici_adi FROM tarifler t 
                   JOIN kullanicilar k ON t.ekleyen_id = k.id 
                   WHERE t.onay_durumu = 1 ORDER BY t.id DESC";
$tarifler = mysqli_query($baglanti, $tarifler_sorgu);


$uyeler = mysqli_query($baglanti, "SELECT * FROM kullanicilar WHERE id != 1 ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetim Karargahı | Suna's Life</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { 
            --bordo: #800000; 
            --altin: #D4AF37;
            --arkaplan: #FFFBF2;
            --krem-koyu: #F5E6D3;
            --yazi: #2D1B1B;
        }

        body { background-color: var(--arkaplan); font-family: 'Poppins', sans-serif; margin: 0; display: flex; color: var(--yazi); }
        .main-content { flex-grow: 1; padding: 40px; margin-left: 280px; min-height: 100vh; }

        /* Banner */
        .admin-banner { 
            background: linear-gradient(135deg, #1A0000 0%, var(--bordo) 100%); 
            border-radius: 35px; color: white; padding: 45px; margin-bottom: 35px; 
            border-bottom: 5px solid var(--altin);
            box-shadow: 0 15px 35px rgba(128, 0, 0, 0.15);
        }


        .nav-pills .nav-link { 
            color: var(--bordo); font-weight: 700; border-radius: 15px; 
            padding: 12px 25px; margin-right: 10px; transition: 0.3s; 
            border: 1px solid var(--krem-koyu); background: white;
        }
        .nav-pills .nav-link.active { 
            background-color: var(--bordo) !important; 
            color: white !important; 
            box-shadow: 0 10px 20px rgba(128, 0, 0, 0.2); 
            border-color: var(--bordo);
        }

  
        .management-card { 
            background: white; border-radius: 35px; padding: 30px; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.02); 
            border: 1px solid var(--krem-koyu); 
        }
        .table thead th { background: var(--arkaplan); border: none; font-size: 0.75rem; text-transform: uppercase; padding: 18px; color: var(--bordo); letter-spacing: 1px; }
        .table td { padding: 18px; vertical-align: middle; border-bottom: 1px solid var(--arkaplan); }

        .btn-action { 
            width: 38px; height: 38px; border-radius: 10px; 
            display: inline-flex; align-items: center; justify-content: center; 
            border: none; transition: 0.3s; color: white !important; text-decoration: none; 
        }
        .btn-approve { background: #2ecc71; }
        .btn-approve:hover { background: #27ae60; transform: scale(1.1); }
        .btn-edit { background: var(--bordo); }
        .btn-edit:hover { background: var(--altin); transform: scale(1.1); }
        .btn-delete { background: #e74c3c; }
        .btn-delete:hover { background: #c0392b; transform: scale(1.1); }

        .wait-badge { background: #e74c3c; color: white; font-size: 0.7rem; padding: 2px 8px; border-radius: 10px; margin-left: 5px; }
        .header-title { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <div class="admin-banner d-flex justify-content-between align-items-center">
            <div>
                <h2 class="header-title fw-bold m-0">Sistem <span>Yönetimi</span></h2>
                <p class="m-0 opacity-75 small fw-bold">ADMIN KONTROL MERKEZİ</p>
            </div>
            <div class="d-flex gap-2">
                <a href="uye-ekle.php" class="btn btn-outline-light btn-sm rounded-pill px-4">Üye Ekle</a>
                <a href="tarif-ekle.php" class="btn btn-warning btn-sm rounded-pill px-4 fw-bold" style="background:var(--altin); border:none; color:var(--bordo);">Yeni Tarif</a>
            </div>
        </div>

        <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="pills-onay-tab" data-bs-toggle="pill" data-bs-target="#pills-onay" type="button">
                    <i class="fas fa-clock me-2"></i>Onay Bekleyenler 
                    <?php if($bekleyen_sayisi > 0): ?><span class="wait-badge"><?= $bekleyen_sayisi ?></span><?php endif; ?>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="pills-tarif-tab" data-bs-toggle="pill" data-bs-target="#pills-tarif" type="button">
                    <i class="fas fa-utensils me-2"></i>Tarif Arşivi
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="pills-uye-tab" data-bs-toggle="pill" data-bs-target="#pills-uye" type="button">
                    <i class="fas fa-users me-2"></i>Mutfak Kadrosu
                </button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
            
            <div class="tab-pane fade show active" id="pills-onay" role="tabpanel">
                <div class="management-card shadow-sm">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Taslak Başlığı</th>
                                <th>Kategori</th>
                                <th>Gönderen Şef</th>
                                <th class="text-end">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($bekleyen_sayisi > 0): ?>
                                <?php while($b = mysqli_fetch_assoc($bekleyenler)): ?>
                                <tr>
                                    <td><strong style="color: var(--bordo);"><?= htmlspecialchars($b['baslik']) ?></strong></td>
                                    <td><span class="badge bg-light text-dark border px-3"><?= $b['kategori'] ?></span></td>
                                    <td class="small fw-bold"><?= $b['kullanici_adi'] ?></td>
                                    <td class="text-end">
                                        <a href="tarif-onayla.php?id=<?= $b['id'] ?>" class="btn-action btn-approve" title="Onayla ve Yayınla"><i class="fas fa-check"></i></a>
                                        <a href="tarif-sil.php?id=<?= $b['id'] ?>" class="btn-action btn-delete" onclick="return confirm('Reddetmek istediğine emin misin?')"><i class="fas fa-times"></i></a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fas fa-check-circle fa-3x mb-3" style="opacity:0.2;"></i>
                                        <p>Şu an onay bekleyen herhangi bir tarif bulunmuyor.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="pills-tarif" role="tabpanel">
                <div class="management-card shadow-sm">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tarif Adı</th>
                                <th>Kategori</th>
                                <th>Yazar</th>
                                <th class="text-end">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($t = mysqli_fetch_assoc($tarifler)): ?>
                            <tr>
                                <td><strong style="color: var(--bordo);"><?= htmlspecialchars($t['baslik']) ?></strong></td>
                                <td><span class="badge bg-light text-dark border px-3"><?= $t['kategori'] ?></span></td>
                                <td class="small fw-bold"><?= $t['kullanici_adi'] ?></td>
                                <td class="text-end">
                                    <a href="tarif-duzenle.php?id=<?= $t['id'] ?>" class="btn-action btn-edit"><i class="fas fa-edit"></i></a>
                                    <a href="tarif-sil.php?id=<?= $t['id'] ?>" class="btn-action btn-delete" onclick="return confirm('Arşivden silinsin mi?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="pills-uye" role="tabpanel">
                <div class="management-card shadow-sm">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Şef Profili</th>
                                <th>E-Posta</th>
                                <th class="text-end">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($u = mysqli_fetch_assoc($uyeler)): ?>
                            <tr>
                                <td><strong style="color: var(--bordo);"><?= htmlspecialchars($u['kullanici_adi']) ?></strong></td>
                                <td class="small text-muted"><?= $u['email'] ?></td>
                                <td class="text-end">
                                    <a href="uye-duzenle.php?id=<?= $u['id'] ?>" class="btn-action btn-edit"><i class="fas fa-user-edit"></i></a>
                                    <a href="uye-sil.php?id=<?= $u['id'] ?>" class="btn-action btn-delete" onclick="return confirm('Üyeliği silmek istediğine emin misin?')"><i class="fas fa-user-times"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>