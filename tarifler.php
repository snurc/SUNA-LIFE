<?php
session_start();
include 'baglanti.php';

if (!isset($_SESSION['kullanici_id'])) {
    header("Location: giris.php");
    exit();
}

$kullanici_id = $_SESSION['kullanici_id'];


if (isset($_GET['ara'])) {
    $ara = mysqli_real_escape_string($baglanti, $_GET['ara']);

    $sorgu = "SELECT * FROM tarifler WHERE baslik LIKE '%$ara%' OR kategori LIKE '%$ara%' ORDER BY id DESC";
} else {
    $sorgu = "SELECT * FROM tarifler ORDER BY id DESC";
}

$videolar = mysqli_query($baglanti, $sorgu);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Video Galeri | Suna's Life</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="tasarim.css">
    <style>
        .video-card { background: white; border-radius: 25px; overflow: hidden; transition: 0.3s; border: 1px solid #f1f2f6; }
        .video-card:hover { transform: translateY(-10px); box-shadow: 0 15px 35px rgba(0,0,0,0.05); }
        .video-thumb { position: relative; width: 100%; height: 200px; background: #000; }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h2 class="fw-bold m-0">Lezzet <span style="color:var(--mercan)">Galerisi</span></h2>
            <div class="search-box">
                <form action="" method="GET" class="d-flex gap-2">
                    <input type="text" name="ara" class="form-control rounded-pill px-4" placeholder="Tarif ara...">
                    <button type="submit" class="btn btn-primary rounded-circle" style="background:var(--nane); border:none;"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>

        <div class="row g-4">
            <?php if(mysqli_num_rows($videolar) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($videolar)): ?>
                    <div class="col-md-4">
                        <div class="video-card shadow-sm h-100">
                            <div class="video-thumb">
                                <?php 
                                    parse_str(parse_url($row['video_url'], PHP_URL_QUERY), $v_vars);
                                    $vid = $v_vars['v'] ?? '';
                                ?>
                                <iframe width="100%" height="100%" src="https://www.youtube.com/embed/<?= $vid ?>" frameborder="0" allowfullscreen></iframe>
                            </div>
                            <div class="p-4">
                                <span class="badge mb-2" style="background:var(--nane); color:white;"><?= $row['kategori'] ?></span>
                                <h5 class="fw-bold text-dark mb-3"><?= htmlspecialchars($row['baslik']) ?></h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="tarif-detay.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold">Tarifi Oku</a>
                                    <small class="text-muted"><i class="fas fa-user-chef me-1"></i> Suna Şef</small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-video-slash fa-4x mb-3 text-muted"></i>
                    <p class="text-muted fs-5">Henüz paylaşılan bir video bulunmuyor.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>