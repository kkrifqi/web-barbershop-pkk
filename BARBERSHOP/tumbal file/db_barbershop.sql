-- ============================================================
--  db_barbershop.sql
--  Jalankan file ini sekali untuk membuat database & tabel.
--  Cara: import via phpMyAdmin, atau jalankan di terminal:
--    mysql -u root -p < db_barbershop.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS db_barbershop
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE db_barbershop;

-- --------------------------------------------------------
-- Tabel: users
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nama       VARCHAR(100)  NOT NULL,
    email      VARCHAR(100)  NOT NULL UNIQUE,
    password   VARCHAR(255)  NOT NULL,          -- disimpan hasil password_hash()
    no_hp      VARCHAR(20)   DEFAULT NULL,
    role       ENUM('admin','user') DEFAULT 'user',
    created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

-- Tambahkan akun admin default (password: admin123)
-- Hash dibuat dengan: password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO users (nama, email, password, role) VALUES
(
    'Admin Crown',
    'admin@crownbarber.com',
    '$2y$12$5yZ5m/PbHfQ0g3Y1J5Kc8.mUxC3I5wY5fOkX2oJvE4u9wqH0tFDIe',
    'admin'
);

-- --------------------------------------------------------
-- Tabel: barbers
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS barbers (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nama       VARCHAR(100)  NOT NULL,
    email      VARCHAR(100)  DEFAULT NULL,
    instagram  VARCHAR(100)  DEFAULT NULL,
    keahlian   VARCHAR(255)  DEFAULT NULL,
    deskripsi  TEXT          DEFAULT NULL,
    foto       VARCHAR(255)  DEFAULT NULL,      -- path file foto, misal: uploads/barber/wahyu.jpg
    status     ENUM('aktif','nonaktif') DEFAULT 'aktif'
);

INSERT INTO barbers (nama, email, instagram, keahlian, deskripsi, status) VALUES
('Wahyu Pratama',  'wahyu@crownbarber.com', '@wahyu_cut', 'Modern Fade, Crop, Classic Gentleman Cut', 'Wahyu telah menekuni dunia barbering selama lebih dari 6 tahun. Berpengalaman dalam modern fade, crop, dan classic gentleman cut.', 'aktif'),
('Ade Shawarma',   'ade@crownbarber.com',   '@ade_cut',   'Beard Trim, Styling',                      'Ade adalah spesialis beard-trim & styling. Setiap detail dikerjakan dengan presisi dan kenyamanan pelanggan.',               'aktif'),
('Eko Saputra',    'eko@crownbarber.com',   '@eko_cut',   'Textured Cut, Messy Modern Style',         'Memiliki pengalaman internasional dan lulusan akademi barber ternama. Ahli dalam textured cut & messy modern style.',          'aktif');

-- --------------------------------------------------------
-- Tabel: services
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS services (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    nama     VARCHAR(100)              NOT NULL,
    kategori ENUM('Dewasa','Anak-anak') NOT NULL,
    harga    INT                        NOT NULL,   -- dalam rupiah
    durasi   INT                        NOT NULL    -- dalam menit
);

INSERT INTO services (nama, kategori, harga, durasi) VALUES
-- Dewasa
('Cukur Biasa',          'Dewasa',    35000, 30),
('Cukur + Cuci',         'Dewasa',    45000, 45),
('Cukur + Cuci + Pijat', 'Dewasa',    55000, 60),
('Hair Styling',         'Dewasa',    25000, 20),
-- Anak-anak
('Cukur Biasa',          'Anak-anak', 25000, 25),
('Cukur + Cuci',         'Anak-anak', 30000, 35),
('Cukur + Cuci + Pijat', 'Anak-anak', 40000, 50),
('Hair Styling',         'Anak-anak', 20000, 15);

-- --------------------------------------------------------
-- Tabel: gallery
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS gallery (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    foto        VARCHAR(255) NOT NULL,             -- path file, misal: uploads/gallery/foto1.jpg
    uploaded_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------------------
-- Tabel: bookings
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS bookings (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT  NOT NULL,
    barber_id  INT  NOT NULL,
    service_id INT  NOT NULL,
    tanggal    DATE NOT NULL,
    jam        TIME NOT NULL,
    catatan    TEXT DEFAULT NULL,
    status     ENUM('pending','accepted','completed','canceled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)   REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (barber_id) REFERENCES barbers(id)  ON DELETE CASCADE,
    FOREIGN KEY (service_id)REFERENCES services(id) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- Tabel: settings
-- Menyimpan konten barbershop yang bisa diedit dari admin
-- Pakai sistem key-value supaya fleksibel
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS settings (
    kunci VARCHAR(100) PRIMARY KEY,
    nilai TEXT         DEFAULT NULL
);

INSERT INTO settings (kunci, nilai) VALUES
('nama_barbershop', 'Crown Barbershop'),
('tagline',         'Look Sharp. Feel Royal. The Crown.'),
('alamat',          'Jl. Melati Indah No. 27, Kel. Sukamurni, Kec. Cendana\nKota Seruni 45123 – Indonesia'),
('telepon',         '(021) 8890 1122'),
('email',           'info@crownbarber.com'),
('about_p1',        'Di Crown Barbershop, kami percaya bahwa rambut Anda adalah mahkota yang menentukan karakter Anda. Kami bukan sekadar tempat potong rambut; kami adalah destinasi bagi pria yang menghargai detail, kenyamanan, dan presisi.'),
('about_p2',        'Datanglah, nikmati pelayanan terbaik kami, dan biarkan barber ahli kami memberikan transformasi yang membuat Anda keluar dengan kepercayaan diri maksimal.'),
('about_p3',        'Mulai dari classic pompadour, modern skin fade, hingga ritual cukur jenggot tradisional — di Crown, setiap pelanggan dilayani layaknya raja.'),
('jam_senin',       '10.00 - 20.00'),
('jam_selasa',      '11.00 - 21.00'),
('jam_rabu',        '09.00 - 20.00'),
('jam_kamis',       '09.00 - 20.00'),
('jam_jumat',       'Libur'),
('jam_sabtu',       '12.00 - 22.00'),
('jam_minggu',      '08.00 - 21.00'),
('instagram',       '@crownbarbershop'),
('facebook',        'Crown Barbershop Official'),
('tiktok',          '@crown.barber');
