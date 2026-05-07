<?php

include 'baglanti.php';
mysqli_set_charset($baglanti, "utf8mb4");


$resim_havuzu = [
    'Tatlılar' => [
        'https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?q=80&w=800',
        'https://images.unsplash.com/photo-1551024506-0bccd828d307?q=80&w=800',
        'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?q=80&w=800'
    ],
    'Et Yemekleri' => [
        'https://images.unsplash.com/photo-1600891964092-4316c288032e?q=80&w=800',
        'https://images.unsplash.com/photo-1529692236671-f1f6cf9683ba?q=80&w=800',
        'https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=800'
    ],
    'Sebze Yemekleri' => [
        'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?q=80&w=800',
        'https://images.unsplash.com/photo-1540420773420-3366772f4999?q=80&w=800'
    ],
    'Tavuk Yemekleri' => [
        'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?q=80&w=800',
        'https://images.unsplash.com/photo-1588168333986-5078d3ae3976?q=80&w=800'
    ],
    'Makarnalar' => [
        'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?q=80&w=800',
        'https://images.unsplash.com/photo-1551183053-bf91a1d81141?q=80&w=800'
    ],
    'Varsayilan' => [
        'https://images.unsplash.com/photo-1495195134817-a165d42929ce?q=80&w=800',
        'https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=800'
    ]
];


$sorgu = mysqli_query($baglanti, "SELECT id, kategori FROM tarifler");
$guncellenen = 0;

while ($tarif = mysqli_fetch_assoc($sorgu)) {
    $id = $tarif['id'];
    $kategori = $tarif['kategori'];
    

    $havuz = isset($resim_havuzu[$kategori]) ? $resim_havuzu[$kategori] : $resim_havuzu['Varsayilan'];
    
  
    $rastgele_resim = $havuz[array_rand($havuz)];
    
   
    mysqli_query($baglanti, "UPDATE tarifler SET tarif_resim = '$rastgele_resim' WHERE id = $id");
    $guncellenen++;
}

echo "<h2>Tebrikler Şefim! Başarıyla $guncellenen adet tarife otomatik resim atandı.</h2>";
?>