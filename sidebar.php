<?php 
$aktif = basename($_SERVER['PHP_SELF']); 
$kullanici_id = $_SESSION['kullanici_id'] ?? 0;
?>
<aside class="sidebar-container">
    <div class="sidebar-header">
        <a href="panel.php" class="brand-main">Suna's <span>Life</span></a>
    </div>
    
    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="javascript:void(0);" onclick="sesliKomutBaslat()" id="mic-btn" class="voice-btn">
                    <div class="icon-box mic-icon-box"><i class="fas fa-microphone"></i></div> 
                    <span id="mic-text">Sesli Kontrolü Aç</span>
                </a>
            </li>

            <li>
                <a href="panel.php" class="<?= ($aktif=='panel.php')?'active':'' ?>">
                    <div class="icon-box"><i class="fas fa-home"></i></div> 
                    <span>ANA SAYFA</span>
                </a>
            </li>
            
            <li>
                <a href="tarif-ekle.php" class="<?= ($aktif=='tarif-ekle.php')?'active':'' ?>">
                    <div class="icon-box"><i class="fas fa-plus-circle"></i></div> 
                    <span>Tarif Yayınla</span>
                </a>
            </li>

            <li class="menu-divider">KATEGORİLER</li>
            <li>
                <a href="videolu-tarifler.php" class="<?= ($aktif=='videolu-tarifler.php')?'active':'' ?>">
                    <div class="icon-box"><i class="fas fa-video"></i></div> 
                    <span>Videolu Tarifler</span>
                </a>
            </li>
            <li>
                <a href="resimli-tarifler.php" class="<?= ($aktif=='resimli-tarifler.php')?'active':'' ?>">
                    <div class="icon-box"><i class="fas fa-camera-retro"></i></div> 
                    <span>Pratik Tarifler</span>
                </a>
            </li>

            <li>
                <a href="suna-ai.php" class="<?= ($aktif=='suna-ai.php')?'active ai-active':'' ?>" style="border: 1px dashed #800000; margin-top: 5px; background: rgba(128, 0, 0, 0.05);">
                    <div class="icon-box ai-icon-box"><i class="fas fa-robot"></i></div> 
                    <span style="font-weight: 700;">Suna AI Asistan</span>
                </a>
            </li>
            
            <li>
                <a href="mesaj-gonder.php" class="<?= ($aktif=='mesaj-gonder.php')?'active':'' ?>">
                    <div class="icon-box"><i class="fas fa-paper-plane"></i></div> 
                    <span>Mesaj Gönder</span>
                </a>
            </li>

            <?php if($kullanici_id == 1): ?>
                <li class="menu-divider">YÖNETİM</li>
                <li>
                    <a href="yonetim.php" class="<?= ($aktif=='yonetim.php')?'active':'' ?>">
                        <div class="icon-box"><i class="fas fa-crown"></i></div> 
                        <span>Yönetim Karargahı</span>
                    </a>
                </li>
                <li>
                    <a href="takipciler.php" class="<?= ($aktif=='takipciler.php')?'active':'' ?>">
                        <div class="icon-box"><i class="fas fa-users"></i></div> 
                        <span>Takipçiler</span>
                    </a>
                </li>
                <li>
                    <a href="mesajlar.php" class="<?= ($aktif=='mesajlar.php')?'active':'' ?>">
                        <div class="icon-box"><i class="fas fa-envelope"></i></div> 
                        <span>Gelen Mesajlar</span>
                    </a>
                </li>
            <?php endif; ?>

            <li class="mt-4 border-top border-secondary pt-3 pb-5"> 
                <a href="cikis.php" class="logout-link">
                    <div class="icon-box logout-icon"><i class="fas fa-power-off"></i></div> 
                    <span>Güvenli Çıkış</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>

<style>
 
    :root { --bordo: #800000; --bordo-acik: #a52a2a; --krem: #FFFBF2; --krem-koyu: #F5E6D3; }
    .sidebar-container { width: 280px; height: 100vh; background: var(--krem); position: fixed; left: 0; top: 0; box-shadow: 10px 0 30px rgba(0, 0, 0, 0.05); display: flex; flex-direction: column; z-index: 1000; border-right: 2px solid var(--krem-koyu); overflow-y: auto; }
    .sidebar-header { padding: 45px 30px; text-align: left; }
    .brand-main { font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 800; color: var(--bordo); text-decoration: none; }
    .brand-main span { color: var(--bordo-acik); }
    .sidebar-nav { flex-grow: 1; padding: 0 15px; }
    .sidebar-nav ul { list-style: none; padding: 0; }
    .sidebar-nav a { display: flex; align-items: center; padding: 14px 20px; color: #5a4a42; text-decoration: none; border-radius: 18px; margin-bottom: 8px; transition: 0.4s; font-size: 0.95rem; }
    .sidebar-nav a:hover, .sidebar-nav a.active { background: var(--bordo); color: #ffffff !important; transform: translateX(5px); }
    .icon-box { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: var(--krem-koyu); border-radius: 12px; margin-right: 15px; color: var(--bordo); border: 1px solid rgba(128, 0, 0, 0.1); }
    .sidebar-nav a.active .icon-box, .sidebar-nav a:hover .icon-box { background: rgba(255, 255, 255, 0.2); color: #ffffff; }
    .voice-btn { border: 1px solid var(--bordo) !important; color: var(--bordo) !important; font-weight: bold; }
    .pulse-red { animation: pulse-bordo-anim 2s infinite; background: rgba(128, 0, 0, 0.2) !important; }
    @keyframes pulse-bordo-anim { 0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(128, 0, 0, 0.2); } 70% { transform: scale(1.02); box-shadow: 0 0 0 10px rgba(128, 0, 0, 0); } 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(128, 0, 0, 0); } }
    .menu-divider { font-size: 0.75rem; font-weight: 800; color: var(--bordo); opacity: 0.5; padding: 30px 20px 12px; text-transform: uppercase; letter-spacing: 2px; }
    .logout-link { color: #d63031 !important; border: 1px solid rgba(214, 48, 49, 0.1); }
</style>

<script>
let recognition;
let isListening = false;

function sesliKomutBaslat() {

    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    
    if (!SpeechRecognition) {
        alert("Üzgünüm, tarayıcınız ses tanıma teknolojisini desteklemiyor. Lütfen güncel bir Chrome kullanın.");
        return;
    }

   
    if (isListening) {
        recognition.stop();
        return;
    }

    recognition = new SpeechRecognition();
    recognition.lang = 'tr-TR';
    recognition.continuous = true; 
    recognition.interimResults = false;

    recognition.onstart = () => {
        isListening = true;
        const btn = document.getElementById('mic-btn');
        const text = document.getElementById('mic-text');
        btn.classList.add('pulse-red');
        text.innerText = "Dinliyorum...";
        console.log("Ses tanıma başlatıldı.");
    };

    recognition.onresult = (event) => {
        const last = event.results.length - 1;
        const command = event.results[last][0].transcript.toLowerCase().trim();
        
        console.log("Algılanan Komut: " + command);

      
        if (command.includes("ana sayfa") || command.includes("paneli aç")) {
            window.location.href = "panel.php";
        } 
        else if (command.includes("tarif ekle") || command.includes("yeni tarif")) {
            window.location.href = "tarif-ekle.php";
        }
        else if (command.includes("asistan") || command.includes("robot")) {
            window.location.href = "suna-ai.php";
        }
        else if (command.includes("videolu") || command.includes("video")) {
            window.location.href = "videolu-tarifler.php";
        }
        else if (command.includes("pratik") || command.includes("resimli")) {
            window.location.href = "resimli-tarifler.php";
        }

        else if (command.includes("tarifi") || command.includes("bul") || command.includes("getir")) {
            let search = command.replace("tarifi", "").replace("bul", "").replace("getir", "").replace("aç", "").trim();
            if (search.length > 1) {
                window.location.href = "resimli-tarifler.php?ara=" + encodeURIComponent(search);
            }
        }
    };

    recognition.onerror = (event) => {
        console.error("Hata oluştu: " + event.error);
        if (event.error === 'not-allowed') {
            alert("Mikrofon izni verilmedi! Lütfen adres çubuğundaki kilit simgesinden izin verin.");
        }
        isListening = false;
    };

    recognition.onend = () => {
        isListening = false;
        const btn = document.getElementById('mic-btn');
        const text = document.getElementById('mic-text');
        btn.classList.remove('pulse-red');
        text.innerText = "Sesli Kontrolü Aç";
        console.log("Ses tanıma sona erdi.");
    };

    recognition.start();
}
</script>