<?php
// ============================================================
//  barber.php — Halaman utama user
//  Menggantikan barber.html
//  Semua konten dinamis dibaca dari tabel settings & barbers
// ============================================================

require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/cek_session.php';

// ── Ambil semua settings sekaligus ──────────────────────────
$stmtSet = $pdo->query("SELECT kunci, nilai FROM settings");
$s = [];
foreach ($stmtSet->fetchAll() as $row) {
    $s[$row['kunci']] = $row['nilai'];
}

// Helper: ambil nilai setting, fallback ke string kosong
function set(string $key, array $s): string {
    return htmlspecialchars($s[$key] ?? '', ENT_QUOTES, 'UTF-8');
}

// ── Ambil daftar barber aktif ────────────────────────────────
$stmtBarber = $pdo->query("SELECT * FROM barbers WHERE status = 'aktif' ORDER BY id ASC");
$barbers    = $stmtBarber->fetchAll();

// ── Ambil layanan per kategori ───────────────────────────────
$stmtSvc  = $pdo->query("SELECT * FROM services ORDER BY kategori, harga ASC");
$services = $stmtSvc->fetchAll();

$svcDewasa  = array_filter($services, fn($r) => $r['kategori'] === 'Dewasa');
$svcAnak    = array_filter($services, fn($r) => $r['kategori'] === 'Anak-anak');

// ── Ambil foto gallery ───────────────────────────────────────
$stmtGal = $pdo->query("SELECT * FROM gallery ORDER BY uploaded_at DESC");
$gallery = $stmtGal->fetchAll();

// ── Jam operasional ──────────────────────────────────────────
$hariList = [
    'Senin'   => $s['jam_senin']   ?? '',
    'Selasa'  => $s['jam_selasa']  ?? '',
    'Rabu'    => $s['jam_rabu']    ?? '',
    'Kamis'   => $s['jam_kamis']   ?? '',
    "Jum'at"  => $s['jam_jumat']   ?? '',
    'Sabtu'   => $s['jam_sabtu']   ?? '',
    'Minggu'  => $s['jam_minggu']  ?? '',
];

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= set('nama_barbershop', $s) ?> | Profesional & Clean</title>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Libre+Franklin:ital,wght@0,100..900;1,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Zalando+Sans+SemiExpanded:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/barber.css">
</head>
<body>

    <!-- NAVBAR -->
    <div class="cont-navbar">
        <nav class="navbar">
            <a href="#Aboutus">ABOUT US</a>
            <a href="#service">SERVICE</a>
            <a href="#feedback">CONTACT US</a>
            <a href="#gallery">GALLERY</a>
            <a href="#feedback">FEEDBACK</a>
            <a href="#lokasi-jam">LOCATION</a>
            <div id="nav-auth">
                <a href="../login-register/login.html">
                    <ion-icon name="person-circle-outline"></ion-icon>
                </a>
            </div>
        </nav>
    </div>


    <!-- HOME / HERO -->
    <div class="home">
        <div class="pic">
            <img src="../assets/img/logo.svg" alt="<?= set('nama_barbershop', $s) ?> Logo">
        </div>
        <div class="cont-book">
            <a href="../booking-page/index.html">BOOK</a>
        </div>
    </div>


    <!-- ABOUT US -->
    <div class="cont-Aboutus" id="Aboutus">
        <div class="Aboutus">
            <h1>About Us</h1>
            <div class="line"></div>

            <div class="Aboutus-content">
                <div class="Aboutus-left">
                    <div class="Aboutus-left-ktkt">
                        <h2>Tentang Kami</h2>
                        <h3><?= nl2br(set('tagline', $s)) ?></h3>
                    </div>
                    <img src="../assets/img/mount-batur.jpg" alt="<?= set('nama_barbershop', $s) ?>">
                </div>

                <div class="Aboutus-right">
                    <p class="description" id="pertama">
                        <?= set('about_p1', $s) ?>
                    </p>
                    <p class="description">
                        <?= set('about_p2', $s) ?>
                    </p>
                    <p class="description sub-text">
                        <?= set('about_p3', $s) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>


    <!-- HERO IMAGE -->
    <div class="img-wrap">
        <img src="../assets/img/background-barber.png" class="img" alt="">
        <div class="overlay"></div>
    </div>
    <div class="fade-transition"></div>


    <!-- SERVICE -->
    <div class="service" id="service">
        <div class="box-service">
            <div class="service-wrapper">
                <h2 class="service-title">Daftar Harga</h2>

                <!-- Kategori Dewasa -->
                <div class="service-category" id="adultCategory">
                    <h3>Dewasa</h3>
                    <ul>
                        <?php foreach ($svcDewasa as $item): ?>
                        <li>
                            <span><?= htmlspecialchars($item['nama']) ?></span>
                            <span>Rp <?= number_format($item['harga'], 0, ',', '.') ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Kategori Anak-anak -->
                <div class="service-category" id="kidsCategory">
                    <h3>Anak-anak</h3>
                    <ul>
                        <?php foreach ($svcAnak as $item): ?>
                        <li>
                            <span><?= htmlspecialchars($item['nama']) ?></span>
                            <span>Rp <?= number_format($item['harga'], 0, ',', '.') ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="title-service">
            <h1>service</h1>
            <div class="icon">
                <div class="icon-service" id="adultBtn">
                    <img src="../assets/img/3.svg" alt="Dewasa">
                </div>
                <div class="icon-service" id="kidsBtn">
                    <img src="../assets/img/4.svg" alt="Anak-anak">
                </div>
            </div>
            <div class="caption">
                <h2 id="captionTitle">Silakan pilih kategori</h2>
                <p id="captionText">Pilih ikon di atas untuk melihat daftar harganya.</p>
            </div>
        </div>
    </div>


    <!-- BARBERS -->
    <div class="pencukur">
        <div class="title-pencukur">
            <h1>Barbers</h1>
        </div>

        <div class="cont-pencukur">
            <?php foreach ($barbers as $b): ?>
            <div class="cont-perorang">
                <div class="img-pen">
                    <img src="<?= $b['foto'] ? htmlspecialchars('../' . $b['foto']) : '../assets/img/mount-batur.jpg' ?>"
                         alt="<?= htmlspecialchars($b['nama']) ?>">
                </div>
                <div class="p-pen">
                    <h2 class="nama-barber"><?= htmlspecialchars(strtoupper($b['nama'])) ?></h2>
                    <div class="contact-barber">
                        <?php if ($b['email']): ?>
                        <div class="row-contact">
                            <ion-icon name="mail-outline" class="icon-contact"></ion-icon>
                            <a href="mailto:<?= htmlspecialchars($b['email']) ?>"><?= htmlspecialchars($b['email']) ?></a>
                        </div>
                        <?php endif; ?>
                        <?php if ($b['instagram']): ?>
                        <div class="row-contact">
                            <ion-icon name="logo-instagram" class="icon-contact"></ion-icon>
                            <a href="#"><?= htmlspecialchars($b['instagram']) ?></a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <p class="desc-barber"><?= htmlspecialchars($b['deskripsi'] ?? '') ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>


    <!-- GALLERY -->
    <div id="gallery">
        <div class="title-gallery">
            <h1>Gallery</h1>
        </div>
        <div class="gallery">
            <?php if ($gallery): ?>
                <?php foreach ($gallery as $foto): ?>
                <img src="../<?= htmlspecialchars($foto['foto']) ?>" alt="Gallery">
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback kalau gallery masih kosong -->
                <img src="../assets/img/mount-batur.jpg" alt="Gallery 1">
                <img src="../assets/img/mount-batur.jpg" alt="Gallery 2">
                <img src="../assets/img/mount-batur.jpg" alt="Gallery 3">
            <?php endif; ?>
        </div>
    </div>


    <!-- LOKASI & JAM -->
    <div class="lokasi-jam" id="lokasi-jam">
        <div class="title-lok-jam">
            <h1>Lokasi & Jam</h1>
        </div>

        <div class="lokjam-wrapper">
            <div class="lokasi-box">
                <div class="lokasi-title">
                    <h2>Layanan Kami Berlokasi di</h2>
                </div>
                <div class="map-frame">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12856.305090615931!2d107.53946956769138!3d-7.041821542234824!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e8d93186776d%3A0x4c85be42268f3b45!2sPolresta%20Bandung!5e0!3m2!1sid!2sid!4v1771549862886!5m2!1sid!2sid"
                        allowfullscreen="" loading="lazy">
                    </iframe>
                </div>
                <p class="alamat">
                    <?= nl2br(set('alamat', $s)) ?>
                </p>
            </div>

            <div class="jam-box">
                <h2>Jam Operasional</h2>
                <div class="jam-list">
                    <?php foreach ($hariList as $hari => $jam): ?>
                    <div class="jam-row">
                        <span><?= htmlspecialchars($hari) ?></span>
                        <span><?= htmlspecialchars($jam) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>


    <!-- FEEDBACK -->
    <div class="feedback" id="feedback">
        <div class="feedback-container">
            <h1 class="feedback-title">Feedback</h1>
            <p class="feedback-subtitle">
                Ceritakan pengalaman Anda di <?= set('nama_barbershop', $s) ?>.<br>
                Masukan Anda membantu kami menjadi lebih baik.
            </p>
            <form class="feedback-form">
                <div class="row-two">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" placeholder="Nama Anda">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" placeholder="Alamat email">
                    </div>
                </div>
                <div class="form-group">
                    <label>Pesan</label>
                    <textarea placeholder="Tuliskan feedback Anda..."></textarea>
                </div>
                <button type="submit" class="btn-send">Kirim</button>
            </form>
        </div>
    </div>


    <!-- FOOTER -->
    <div class="footer">
        <div class="footer-left">
            <img src="../assets/img/log.svg" alt="Crown Logo" class="footer-logo">
            <div class="footer-brand">
                <h2>CROWN</h2>
                <span>BARBERSHOP</span>
            </div>
        </div>
        <div class="footer-center">
            <a href="#"><ion-icon name="logo-facebook"></ion-icon></a>
            <a href="#"><ion-icon name="logo-instagram"></ion-icon></a>
            <a href="#"><ion-icon name="logo-tiktok"></ion-icon></a>
        </div>
        <div class="footer-right">
            <p><?= set('telepon', $s) ?></p>
            <p><?= set('email', $s) ?></p>
        </div>
    </div>

    <script src="../assets/js/barber.js"></script>

</body>
</html>
