# assets/

Folder terpusat untuk semua resource statis BARBERSHOP project.

## Struktur

```
assets/
├── css/
│   ├── global.css     ← CSS variables + utilities, WAJIB diimport di semua halaman
│   ├── booking.css    ← Khusus booking-page/
│   ├── admin.css      ← Khusus main-page/admin.html
│   └── barber.css     ← Khusus main-page/barber.html
│
├── js/
│   ├── admin.js       ← Logic admin (navigasi, CRUD booking/barber/service/gallery)
│   └── barber.js      ← Logic barber page (toggle kategori service)
│
└── img/
    ├── crown.png
    ├── crown-barber.png   ← rename dari CrownBarber.png
    └── crown-lands.png    ← rename dari CrownLands.png
```

## Cara pakai di HTML

### Booking page (misal: booking-page/index.html)
```html
<link rel="stylesheet" href="../assets/css/global.css">
<link rel="stylesheet" href="../assets/css/booking.css">
```

### Admin page (main-page/admin.html)
```html
<link rel="stylesheet" href="../assets/css/global.css">
<link rel="stylesheet" href="../assets/css/admin.css">
<script src="../assets/js/admin.js" defer></script>
```

### Barber page (main-page/barber.html)
```html
<link rel="stylesheet" href="../assets/css/global.css">
<link rel="stylesheet" href="../assets/css/barber.css">
<script src="../assets/js/barber.js" defer></script>
```

## Aturan

- Jangan simpan gambar di dalam folder halaman (booking-page, login-register, main-page)
- Semua gambar masuk ke `assets/img/`
- Nama file gambar pakai **kebab-case** lowercase: `crown-barber.png` bukan `CrownBarber.png`
- Kalau mau ubah warna tema, cukup edit variabel di `global.css` bagian `:root`
