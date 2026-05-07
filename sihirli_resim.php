<?php

include 'baglanti.php';
mysqli_set_charset($baglanti, "utf8mb4"); 


$sorgu = mysqli_query($baglanti, "SELECT id, baslik, kategori FROM tarifler");
$guncellenen = 0;

while ($tarif = mysqli_fetch_assoc($sorgu)) {
    $id = $tarif['id'];
  
    $baslik = mb_strtolower($tarif['baslik'], 'UTF-8');
    $resim_url = "";


    if (strpos($baslik, 'limon') !== false) {
        $resim_url = 'https://images.unsplash.com/photo-1519869325930-281384150729?q=80&w=800'; // Limonlu kek
    } elseif (strpos($baslik, 'lasagna') !== false || strpos($baslik, 'lazanya') !== false) {
        $resim_url = 'https://images.unsplash.com/photo-1574894709920-11b28e7367e3?q=80&w=800'; // Lazanya
    } elseif (strpos($baslik, 'burger') !== false || strpos($baslik, 'hamburger') !== false) {
        $resim_url = 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?q=80&w=800'; // Burger
    } elseif (strpos($baslik, 'sezar') !== false || strpos($baslik, 'salata') !== false) {
        $resim_url = 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?q=80&w=800'; // Salata
    } elseif (strpos($baslik, 'nachos') !== false) {
        $resim_url = 'https://images.unsplash.com/photo-1513456852971-30c0b8199d4d?q=80&w=800'; // Nachos
    } elseif (strpos($baslik, 'çikolata') !== false || strpos($baslik, 'supangle') !== false || strpos($baslik, 'profiterol') !== false) {
        $resim_url = 'https://images.unsplash.com/photo-1605807646983-377bc5a76493?q=80&w=800'; // Çikolatalı tatlılar
    } elseif (strpos($baslik, 'cheesecake') !== false) {
        $resim_url = 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?q=80&w=800'; // Cheesecake
    } elseif (strpos($baslik, 'kavurma') !== false || strpos($baslik, 'kebab') !== false || strpos($baslik, 'et') !== false || strpos($baslik, 'hünkar') !== false) {
        $resim_url = 'https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=800'; // Et yemekleri
    } elseif (strpos($baslik, 'somon') !== false || strpos($baslik, 'balık') !== false || strpos($baslik, 'karides') !== false) {
        $resim_url = 'https://images.unsplash.com/photo-1615141982883-c7da0e698b00?q=80&w=800'; // Deniz ürünleri
    } elseif (strpos($baslik, 'tavuk') !== false) {
        $resim_url = 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?q=80&w=800'; // Tavuk
    } elseif (strpos($baslik, 'çorba') !== false) {
        $resim_url = 'https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=800'; // Çorba
    } elseif (strpos($baslik, 'mantar') !== false) {
        $resim_url = 'https://images.unsplash.com/photo-1511689660979-10d2b1aada49?q=80&w=800'; // Mantar
    } elseif (strpos($baslik, 'kahvaltı') !== false || strpos($baslik, 'menemen') !== false || strpos($baslik, 'yumurta') !== false) {
        $resim_url = 'https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?q=80&w=800'; // Kahvaltı
    } elseif (strpos($baslik, 'waffle') !== false || strpos($baslik, 'krep') !== false) {
        $resim_url = 'https://images.unsplash.com/photo-1562376552-0d160a2f1437?q=80&w=800'; // Waffle
    } elseif (strpos($baslik, 'pilav') !== false || strpos($baslik, 'risotto') !== false) {
        $resim_url = 'https://images.unsplash.com/photo-1603496987351-f84a3ba5ec85?q=80&w=800'; // Pilav
    } elseif (strpos($baslik, 'enginar') !== false || strpos($baslik, 'sebze') !== false || strpos($baslik, 'ratatouille') !== false) {
        $resim_url = 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?q=80&w=800'; // Sebze
    } 

    else {
        $kategori = $tarif['kategori'];
        if ($kategori == 'Tatlılar') $resim_url = 'https://images.unsplash.com/photo-1551024506-0bccd828d307?q=80&w=800';
        else if ($kategori == 'Et Yemekleri') $resim_url = 'https://images.unsplash.com/photo-1600891964092-4316c288032e?q=80&w=800';
        else if ($kategori == 'Hamur İşleri') $resim_url = 'https://images.unsplash.com/photo-1509440159596-0249088772ff?q=80&w=800';
        else $resim_url = 'https://images.unsplash.com/photo-1495195134817-a165d42929ce?q=80&w=800'; // Ne idüğü belirsizse bunu koy
    }

    
    if($resim_url != "") {
        mysqli_query($baglanti, "UPDATE tarifler SET tarif_resim = '$resim_url' WHERE id = $id");
        $guncellenen++;
    }
}

echo "<div style='font-family:sans-serif; text-align:center; padding:50px; background:#ebffef; color:#155724;'>
        <h1>✨ Sihir Gerçekleşti Şefim!</h1>
        <p>Tam <b>$guncellenen</b> adet tarifin fotoğrafı, ismine birebir uyacak şekilde başarıyla güncellendi.</p>
        <a href='index.php' style='padding:10px 20px; background:#155724; color:white; text-decoration:none; border-radius:5px;'>Vitrini Görmeye Git</a>
      </div>";
?>