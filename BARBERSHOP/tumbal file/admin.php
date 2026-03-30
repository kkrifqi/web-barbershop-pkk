<?php
// ============================================================
//  admin.php — Admin Panel Crown Barbershop
//  File ini menggantikan admin.html
//  guardAdmin() akan redirect ke login kalau bukan admin
// ============================================================

require_once __DIR__ . '/../config/cek_session.php';
  // ← Kalau bukan admin, langsung redirect ke login

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crown Barbershop | Admin Panel</title>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<div class="admin-wrapper">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <h2>CROWN</h2>
            <span>ADMIN PANEL</span>
        </div>

        <nav class="sidebar-nav">
            <a href="#" class="nav-link active" data-page="dashboard">
                <ion-icon name="grid-outline"></ion-icon> Dashboard
            </a>
            <a href="#" class="nav-link" data-page="booking">
                <ion-icon name="calendar-outline"></ion-icon> Booking
                <span class="nav-badge" id="badge-booking">4</span>
            </a>
            <a href="#" class="nav-link" data-page="barber">
                <ion-icon name="people-outline"></ion-icon> Barber
            </a>
            <a href="#" class="nav-link" data-page="service">
                <ion-icon name="list-outline"></ion-icon> Service
            </a>
            <a href="#" class="nav-link" data-page="gallery">
                <ion-icon name="images-outline"></ion-icon> Gallery
            </a>
            <a href="#" class="nav-link" data-page="settings">
                <ion-icon name="settings-outline"></ion-icon> Settings
            </a>
        </nav>

        <div class="sidebar-footer">
            <ion-icon name="person-circle-outline"></ion-icon>
            <!-- Tampilkan nama admin dari session -->
            <span><?= htmlspecialchars($namaUser) ?></span>
            <button class="btn-logout-admin" id="btn-logout-admin" title="Logout">
                <ion-icon name="log-out-outline"></ion-icon>
            </button>
        </div>
    </div>
    <!-- END SIDEBAR -->


    <!-- MAIN CONTENT -->
    <div class="main-content">

        <!-- TOPBAR -->
        <div class="topbar">
            <h3 class="topbar-title" id="topbar-title">Dashboard</h3>
            <a href="../pages/barber.php" class="btn-ke-user">
                <ion-icon name="arrow-back-outline"></ion-icon>
                Lihat Website
            </a>
        </div>


        <!-- HALAMAN: DASHBOARD -->
        <div class="page active" id="page-dashboard">

            <div class="section-title">
                <h1>Dashboard</h1>
                <div class="line"></div>
            </div>

            <div class="kartu-grid">
                <div class="kartu">
                    <div class="kartu-icon"><ion-icon name="calendar-outline"></ion-icon></div>
                    <div class="kartu-info">
                        <p class="kartu-label">Booking Hari Ini</p>
                        <h2 class="kartu-angka">12</h2>
                    </div>
                </div>
                <div class="kartu kartu--yellow">
                    <div class="kartu-icon"><ion-icon name="time-outline"></ion-icon></div>
                    <div class="kartu-info">
                        <p class="kartu-label">Booking Pending</p>
                        <h2 class="kartu-angka">4</h2>
                    </div>
                </div>
                <div class="kartu kartu--green">
                    <div class="kartu-icon"><ion-icon name="people-outline"></ion-icon></div>
                    <div class="kartu-info">
                        <p class="kartu-label">Barber Aktif</p>
                        <h2 class="kartu-angka">3</h2>
                    </div>
                </div>
                <div class="kartu">
                    <div class="kartu-icon"><ion-icon name="images-outline"></ion-icon></div>
                    <div class="kartu-info">
                        <p class="kartu-label">Foto Gallery</p>
                        <h2 class="kartu-angka">24</h2>
                    </div>
                </div>
            </div>

            <div class="section-title section-title--mt">
                <h1>Booking Terbaru</h1>
                <div class="line"></div>
            </div>

            <div class="tabel-wrapper">
                <table class="tabel">
                    <thead>
                        <tr>
                            <th>Nama</th><th>Layanan</th><th>Barber</th><th>Jam</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Raka Putra</td><td>Cukur + Cuci</td><td>Wahyu</td><td>10:00</td>
                            <td><span class="status accepted">Accepted</span></td>
                        </tr>
                        <tr>
                            <td>Bima Sakti</td><td>Cukur Biasa</td><td>Ade</td><td>11:30</td>
                            <td><span class="status pending">Pending</span></td>
                        </tr>
                        <tr>
                            <td>Dimas Arya</td><td>Hair Styling</td><td>Eko</td><td>13:00</td>
                            <td><span class="status pending">Pending</span></td>
                        </tr>
                        <tr>
                            <td>Fajar Nugraha</td><td>Cukur + Cuci + Pijat</td><td>Wahyu</td><td>14:30</td>
                            <td><span class="status completed">Completed</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END DASHBOARD -->


        <!-- HALAMAN: BOOKING -->
        <div class="page" id="page-booking">
            <div class="section-title">
                <h1>Booking</h1>
                <div class="line"></div>
            </div>
            <div class="filter-bar">
                <input type="text" class="input-cari" id="input-cari-booking" placeholder="Cari nama pelanggan...">
                <select class="select-filter" id="filter-status">
                    <option value="semua">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="accepted">Accepted</option>
                    <option value="completed">Completed</option>
                    <option value="canceled">Canceled</option>
                </select>
            </div>
            <div class="tabel-wrapper">
                <table class="tabel" id="tabel-booking">
                    <thead>
                        <tr>
                            <th>No</th><th>Nama</th><th>Layanan</th><th>Barber</th>
                            <th>Tanggal</th><th>Jam</th><th>Status</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-booking">
                        <tr data-status="accepted">
                            <td>001</td><td>Raka Putra</td><td>Cukur + Cuci</td><td>Wahyu</td>
                            <td>17 Mar 2025</td><td>10:00</td>
                            <td><span class="status accepted">Accepted</span></td>
                            <td>
                                <button class="btn-aksi btn-edit" onclick="ubahStatus(this,'completed')">Selesai</button>
                                <button class="btn-aksi btn-hapus" onclick="ubahStatus(this,'canceled')">Batal</button>
                            </td>
                        </tr>
                        <tr data-status="pending">
                            <td>002</td><td>Bima Sakti</td><td>Cukur Biasa</td><td>Ade</td>
                            <td>17 Mar 2025</td><td>11:30</td>
                            <td><span class="status pending">Pending</span></td>
                            <td>
                                <button class="btn-aksi btn-edit" onclick="ubahStatus(this,'accepted')">Terima</button>
                                <button class="btn-aksi btn-hapus" onclick="ubahStatus(this,'canceled')">Batal</button>
                            </td>
                        </tr>
                        <tr data-status="pending">
                            <td>003</td><td>Dimas Arya</td><td>Hair Styling</td><td>Eko</td>
                            <td>17 Mar 2025</td><td>13:00</td>
                            <td><span class="status pending">Pending</span></td>
                            <td>
                                <button class="btn-aksi btn-edit" onclick="ubahStatus(this,'accepted')">Terima</button>
                                <button class="btn-aksi btn-hapus" onclick="ubahStatus(this,'canceled')">Batal</button>
                            </td>
                        </tr>
                        <tr data-status="completed">
                            <td>004</td><td>Fajar Nugraha</td><td>Cukur + Cuci + Pijat</td><td>Wahyu</td>
                            <td>17 Mar 2025</td><td>14:30</td>
                            <td><span class="status completed">Completed</span></td>
                            <td>
                                <button class="btn-aksi btn-hapus" onclick="ubahStatus(this,'canceled')">Batal</button>
                            </td>
                        </tr>
                        <tr data-status="canceled">
                            <td>005</td><td>Hendra Kusuma</td><td>Cukur Biasa</td><td>Ade</td>
                            <td>17 Mar 2025</td><td>15:00</td>
                            <td><span class="status canceled">Canceled</span></td>
                            <td>
                                <button class="btn-aksi btn-edit" onclick="ubahStatus(this,'accepted')">Aktifkan</button>
                            </td>
                        </tr>
                        <tr data-status="pending">
                            <td>006</td><td>Kevin Andrian</td><td>Cukur + Cuci</td><td>Eko</td>
                            <td>17 Mar 2025</td><td>16:30</td>
                            <td><span class="status pending">Pending</span></td>
                            <td>
                                <button class="btn-aksi btn-edit" onclick="ubahStatus(this,'accepted')">Terima</button>
                                <button class="btn-aksi btn-hapus" onclick="ubahStatus(this,'canceled')">Batal</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END BOOKING -->


        <!-- HALAMAN: BARBER -->
        <div class="page" id="page-barber">
            <div class="section-title">
                <h1>Barber</h1>
                <div class="line"></div>
            </div>
            <button class="btn-tambah" onclick="tampilkanFormBarber()">
                <ion-icon name="add-outline"></ion-icon> Tambah Barber
            </button>
            <div class="form-card" id="form-barber" style="display:none;">
                <h3 class="form-title">Tambah Barber Baru</h3>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" id="input-nama-barber" placeholder="Nama barber">
                </div>
                <div class="row-two">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="input-email-barber" placeholder="email@crownbarber.com">
                    </div>
                    <div class="form-group">
                        <label>Instagram</label>
                        <input type="text" id="input-ig-barber" placeholder="@handle_ig">
                    </div>
                </div>
                <div class="form-group">
                    <label>Keahlian</label>
                    <input type="text" id="input-keahlian-barber" placeholder="Contoh: Modern Fade, Skin Fade...">
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea id="input-desc-barber" placeholder="Deskripsi singkat barber..."></textarea>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select id="input-status-barber">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Tidak Tersedia</option>
                    </select>
                </div>
                <div class="form-aksi">
                    <button class="btn-simpan" onclick="simpanBarber()">Simpan</button>
                    <button class="btn-batal" onclick="tutupFormBarber()">Batal</button>
                </div>
            </div>
            <div class="cont-pencukur" id="daftar-barber">
                <div class="cont-perorang" id="barber-0">
                    <div class="img-pen"><img src="../assets/img/mount-batur.jpg" alt="Wahyu Pratama"></div>
                    <div class="p-pen">
                        <h2 class="nama-barber">WAHYU PRATAMA</h2>
                        <div class="contact-barber">
                            <div class="row-contact"><ion-icon name="mail-outline" class="icon-contact"></ion-icon><a href="mailto:wahyu@crownbarber.com">wahyu@crownbarber.com</a></div>
                            <div class="row-contact"><ion-icon name="logo-instagram" class="icon-contact"></ion-icon><a href="#">@wahyu_cut</a></div>
                        </div>
                        <p class="desc-barber">Wahyu telah menekuni dunia barbering selama lebih dari 6 tahun.</p>
                        <div class="status-barber"><span class="status accepted">Aktif</span></div>
                        <div class="barber-aksi"><button class="btn-aksi btn-hapus" onclick="hapusBarber('barber-0')"><ion-icon name="trash-outline"></ion-icon> Hapus</button></div>
                    </div>
                </div>
                <div class="cont-perorang" id="barber-1">
                    <div class="img-pen"><img src="../assets/img/mount-batur.jpg" alt="Ade Shawarma"></div>
                    <div class="p-pen">
                        <h2 class="nama-barber">ADE SHAWARMA</h2>
                        <div class="contact-barber">
                            <div class="row-contact"><ion-icon name="mail-outline" class="icon-contact"></ion-icon><a href="mailto:ade@crownbarber.com">ade@crownbarber.com</a></div>
                            <div class="row-contact"><ion-icon name="logo-instagram" class="icon-contact"></ion-icon><a href="#">@ade_cut</a></div>
                        </div>
                        <p class="desc-barber">Ade adalah spesialis beard-trim & styling.</p>
                        <div class="status-barber"><span class="status accepted">Aktif</span></div>
                        <div class="barber-aksi"><button class="btn-aksi btn-hapus" onclick="hapusBarber('barber-1')"><ion-icon name="trash-outline"></ion-icon> Hapus</button></div>
                    </div>
                </div>
                <div class="cont-perorang" id="barber-2">
                    <div class="img-pen"><img src="../assets/img/mount-batur.jpg" alt="Eko Saputra"></div>
                    <div class="p-pen">
                        <h2 class="nama-barber">EKO SAPUTRA</h2>
                        <div class="contact-barber">
                            <div class="row-contact"><ion-icon name="mail-outline" class="icon-contact"></ion-icon><a href="mailto:eko@crownbarber.com">eko@crownbarber.com</a></div>
                            <div class="row-contact"><ion-icon name="logo-instagram" class="icon-contact"></ion-icon><a href="#">@eko_cut</a></div>
                        </div>
                        <p class="desc-barber">Memiliki pengalaman internasional dan lulusan akademi barber ternama.</p>
                        <div class="status-barber"><span class="status accepted">Aktif</span></div>
                        <div class="barber-aksi"><button class="btn-aksi btn-hapus" onclick="hapusBarber('barber-2')"><ion-icon name="trash-outline"></ion-icon> Hapus</button></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END BARBER -->


        <!-- HALAMAN: SERVICE -->
        <div class="page" id="page-service">
            <div class="section-title">
                <h1>Service</h1>
                <div class="line"></div>
            </div>
            <button class="btn-tambah" onclick="tampilkanFormService()">
                <ion-icon name="add-outline"></ion-icon> Tambah Layanan
            </button>
            <div class="form-card" id="form-service" style="display:none;">
                <h3 class="form-title">Tambah Layanan Baru</h3>
                <div class="row-two">
                    <div class="form-group">
                        <label>Nama Layanan</label>
                        <input type="text" id="input-nama-service" placeholder="Nama layanan">
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select id="input-kategori-service">
                            <option value="Dewasa">Dewasa</option>
                            <option value="Anak-anak">Anak-anak</option>
                        </select>
                    </div>
                </div>
                <div class="row-two">
                    <div class="form-group">
                        <label>Harga (Rp)</label>
                        <input type="number" id="input-harga-service" placeholder="35000">
                    </div>
                    <div class="form-group">
                        <label>Durasi (menit)</label>
                        <input type="number" id="input-durasi-service" placeholder="30">
                    </div>
                </div>
                <div class="form-aksi">
                    <button class="btn-simpan" onclick="simpanService()">Simpan</button>
                    <button class="btn-batal" onclick="tutupFormService()">Batal</button>
                </div>
            </div>
            <div class="tab-bar">
                <button class="tab-btn active" onclick="gantiTab(this,'tab-dewasa')">Dewasa</button>
                <button class="tab-btn" onclick="gantiTab(this,'tab-anak')">Anak-anak</button>
            </div>
            <div class="tab-content active" id="tab-dewasa">
                <div class="tabel-wrapper">
                    <table class="tabel">
                        <thead><tr><th>Nama Layanan</th><th>Harga</th><th>Durasi</th><th>Aksi</th></tr></thead>
                        <tbody id="tbody-dewasa">
                            <tr><td>Cukur Biasa</td><td>Rp 35.000</td><td>± 30 menit</td><td><button class="btn-aksi btn-hapus" onclick="hapusBaris(this)">Hapus</button></td></tr>
                            <tr><td>Cukur + Cuci</td><td>Rp 45.000</td><td>± 45 menit</td><td><button class="btn-aksi btn-hapus" onclick="hapusBaris(this)">Hapus</button></td></tr>
                            <tr><td>Cukur + Cuci + Pijat</td><td>Rp 55.000</td><td>± 60 menit</td><td><button class="btn-aksi btn-hapus" onclick="hapusBaris(this)">Hapus</button></td></tr>
                            <tr><td>Hair Styling</td><td>Rp 25.000</td><td>± 20 menit</td><td><button class="btn-aksi btn-hapus" onclick="hapusBaris(this)">Hapus</button></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-content" id="tab-anak">
                <div class="tabel-wrapper">
                    <table class="tabel">
                        <thead><tr><th>Nama Layanan</th><th>Harga</th><th>Durasi</th><th>Aksi</th></tr></thead>
                        <tbody id="tbody-anak">
                            <tr><td>Cukur Biasa</td><td>Rp 25.000</td><td>± 25 menit</td><td><button class="btn-aksi btn-hapus" onclick="hapusBaris(this)">Hapus</button></td></tr>
                            <tr><td>Cukur + Cuci</td><td>Rp 30.000</td><td>± 35 menit</td><td><button class="btn-aksi btn-hapus" onclick="hapusBaris(this)">Hapus</button></td></tr>
                            <tr><td>Cukur + Cuci + Pijat</td><td>Rp 40.000</td><td>± 50 menit</td><td><button class="btn-aksi btn-hapus" onclick="hapusBaris(this)">Hapus</button></td></tr>
                            <tr><td>Hair Styling</td><td>Rp 20.000</td><td>± 15 menit</td><td><button class="btn-aksi btn-hapus" onclick="hapusBaris(this)">Hapus</button></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END SERVICE -->


        <!-- HALAMAN: GALLERY -->
        <div class="page" id="page-gallery">
            <div class="section-title">
                <h1>Gallery</h1>
                <div class="line"></div>
            </div>
            <div class="upload-area">
                <ion-icon name="cloud-upload-outline"></ion-icon>
                <p>Klik untuk upload foto</p>
                <input type="file" id="input-foto" accept="image/*" multiple onchange="uploadFoto(this)">
            </div>
            <div class="gallery-admin" id="gallery-admin">
                <div class="gallery-item"><img src="../assets/img/mount-batur.jpg" alt="Gallery 1"><div class="gallery-overlay"><button class="btn-hapus-foto" onclick="hapusFoto(this)"><ion-icon name="trash-outline"></ion-icon></button></div></div>
                <div class="gallery-item"><img src="../assets/img/mount-batur.jpg" alt="Gallery 2"><div class="gallery-overlay"><button class="btn-hapus-foto" onclick="hapusFoto(this)"><ion-icon name="trash-outline"></ion-icon></button></div></div>
                <div class="gallery-item"><img src="../assets/img/mount-batur.jpg" alt="Gallery 3"><div class="gallery-overlay"><button class="btn-hapus-foto" onclick="hapusFoto(this)"><ion-icon name="trash-outline"></ion-icon></button></div></div>
            </div>
        </div>
        <!-- END GALLERY -->


        <!-- HALAMAN: SETTINGS -->
        <div class="page" id="page-settings">
            <div class="section-title">
                <h1>Settings</h1>
                <div class="line"></div>
            </div>
            <div class="form-card">
                <h3 class="form-title">Informasi Barbershop</h3>
                <div class="form-group"><label>Nama Barbershop</label><input type="text" id="set-nama" value="Crown Barbershop"></div>
                <div class="form-group"><label>Tagline</label><input type="text" id="set-tagline" value="Look Sharp. Feel Royal. The Crown."></div>
                <div class="form-group"><label>Alamat</label><textarea id="set-alamat">Jl. Melati Indah No. 27, Kel. Sukamurni, Kec. Cendana&#10;Kota Seruni 45123 – Indonesia</textarea></div>
                <div class="row-two">
                    <div class="form-group"><label>Nomor Telepon</label><input type="text" id="set-telp" value="(021) 8890 1122"></div>
                    <div class="form-group"><label>Email</label><input type="email" id="set-email" value="info@crownbarber.com"></div>
                </div>
            </div>
            <div class="form-card form-card--mt">
                <h3 class="form-title">Konten About Us</h3>
                <div class="form-group"><label>Paragraf 1</label><textarea id="set-about1">Di Crown Barbershop, kami percaya bahwa rambut Anda adalah mahkota yang menentukan karakter Anda.</textarea></div>
                <div class="form-group"><label>Paragraf 2</label><textarea id="set-about2">Datanglah, nikmati pelayanan terbaik kami, dan biarkan barber ahli kami memberikan transformasi yang membuat Anda keluar dengan kepercayaan diri maksimal.</textarea></div>
                <div class="form-group"><label>Paragraf 3 (Teks miring)</label><textarea id="set-about3">Mulai dari classic pompadour, modern skin fade, hingga ritual cukur jenggot tradisional — di Crown, setiap pelanggan dilayani layaknya raja.</textarea></div>
            </div>
            <div class="form-card form-card--mt">
                <h3 class="form-title">Jam Operasional</h3>
                <div class="jam-edit-list">
                    <div class="jam-edit-row"><span class="jam-hari">Senin</span><input type="text" class="jam-input" value="10.00 - 20.00"></div>
                    <div class="jam-edit-row"><span class="jam-hari">Selasa</span><input type="text" class="jam-input" value="11.00 - 21.00"></div>
                    <div class="jam-edit-row"><span class="jam-hari">Rabu</span><input type="text" class="jam-input" value="09.00 - 20.00"></div>
                    <div class="jam-edit-row"><span class="jam-hari">Kamis</span><input type="text" class="jam-input" value="09.00 - 20.00"></div>
                    <div class="jam-edit-row"><span class="jam-hari jam-hari--libur">Jum'at</span><input type="text" class="jam-input jam-input--libur" value="Libur"></div>
                    <div class="jam-edit-row"><span class="jam-hari">Sabtu</span><input type="text" class="jam-input" value="12.00 - 22.00"></div>
                    <div class="jam-edit-row"><span class="jam-hari">Minggu</span><input type="text" class="jam-input" value="08.00 - 21.00"></div>
                </div>
            </div>
            <div class="form-card form-card--mt">
                <h3 class="form-title">Media Sosial</h3>
                <div class="form-group"><label>Instagram</label><input type="text" id="set-ig" value="@crownbarbershop"></div>
                <div class="form-group"><label>Facebook</label><input type="text" id="set-fb" value="Crown Barbershop Official"></div>
                <div class="form-group"><label>TikTok</label><input type="text" id="set-tiktok" value="@crown.barber"></div>
            </div>
            <button class="btn-simpan-settings" onclick="simpanSettings()">
                <ion-icon name="save-outline"></ion-icon>
                Simpan Semua Perubahan
            </button>
        </div>
        <!-- END SETTINGS -->

    </div>
    <!-- END MAIN CONTENT -->

</div>

<script src="../assets/js/admin.js"></script>

</body>
</html>
