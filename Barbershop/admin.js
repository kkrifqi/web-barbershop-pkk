// ==========================
// NAVIGASI HALAMAN
// ==========================

// Ambil semua nav link dan semua halaman
const navLinks = document.querySelectorAll('.nav-link');
const pages = document.querySelectorAll('.page');
const topbarTitle = document.getElementById('topbar-title');

// Jalankan saat salah satu nav link diklik
navLinks.forEach(function(link) {
    link.addEventListener('click', function(e) {
        e.preventDefault();

        // Ambil nama halaman dari data-page
        const targetPage = this.getAttribute('data-page');

        // Hapus class active dari semua link dan semua halaman
        navLinks.forEach(function(l) { l.classList.remove('active'); });
        pages.forEach(function(p) { p.classList.remove('active'); });

        // Tambah active ke link dan halaman yang diklik
        this.classList.add('active');
        document.getElementById('page-' + targetPage).classList.add('active');

        // Update judul topbar
        const judul = {
            dashboard: 'Dashboard',
            booking: 'Booking',
            barber: 'Barber',
            service: 'Service',
            gallery: 'Gallery',
            settings: 'Settings'
        };
        topbarTitle.textContent = judul[targetPage];
    });
});


// ==========================
// BOOKING - Filter & Cari
// ==========================

const inputCariBooking = document.getElementById('input-cari-booking');
const filterStatus = document.getElementById('filter-status');

// Fungsi filter tabel booking
function filterBooking() {
    var keyword = inputCariBooking.value.toLowerCase();
    var statusDipilih = filterStatus.value;
    var rows = document.querySelectorAll('#tbody-booking tr');

    rows.forEach(function(row) {
        var nama = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        var status = row.getAttribute('data-status');

        var cocoNama = nama.includes(keyword);
        var cocoStatus = (statusDipilih === 'semua') || (status === statusDipilih);

        if (cocoNama && cocoStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Jalankan filter saat mengetik atau memilih
if (inputCariBooking) {
    inputCariBooking.addEventListener('input', filterBooking);
}
if (filterStatus) {
    filterStatus.addEventListener('change', filterBooking);
}


// ==========================
// BOOKING - Ubah Status
// ==========================

// Dipanggil dari tombol di tabel
function ubahStatus(tombol, statusBaru) {
    var row = tombol.closest('tr');
    var spanStatus = row.querySelector('.status');

    // Hapus semua class status lama
    spanStatus.className = 'status';

    // Tambah class dan teks sesuai status baru
    spanStatus.classList.add(statusBaru);

    var teksStatus = {
        accepted: 'Accepted',
        completed: 'Completed',
        canceled: 'Canceled',
        pending: 'Pending'
    };
    spanStatus.textContent = teksStatus[statusBaru];

    // Update data-status di row untuk filter
    row.setAttribute('data-status', statusBaru);

    // Update tombol aksi sesuai status baru
    var kolomAksi = row.querySelector('td:last-child');
    if (statusBaru === 'accepted') {
        kolomAksi.innerHTML = '<button class="btn-aksi btn-edit" onclick="ubahStatus(this, \'completed\')">Selesai</button>' +
                              '<button class="btn-aksi btn-hapus" onclick="ubahStatus(this, \'canceled\')">Batal</button>';
    } else if (statusBaru === 'completed') {
        kolomAksi.innerHTML = '<button class="btn-aksi btn-hapus" onclick="ubahStatus(this, \'canceled\')">Batal</button>';
    } else if (statusBaru === 'canceled') {
        kolomAksi.innerHTML = '<button class="btn-aksi btn-edit" onclick="ubahStatus(this, \'accepted\')">Aktifkan</button>';
    }
}


// ==========================
// BARBER - Form Tambah
// ==========================

function tampilkanFormBarber() {
    var form = document.getElementById('form-barber');
    form.style.display = 'block';

    // Scroll ke form
    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function tutupFormBarber() {
    var form = document.getElementById('form-barber');
    form.style.display = 'none';

    // Kosongkan input
    document.getElementById('input-nama-barber').value = '';
    document.getElementById('input-email-barber').value = '';
    document.getElementById('input-ig-barber').value = '';
    document.getElementById('input-keahlian-barber').value = '';
    document.getElementById('input-desc-barber').value = '';
}

function simpanBarber() {
    var nama = document.getElementById('input-nama-barber').value.trim();
    var email = document.getElementById('input-email-barber').value.trim();
    var ig = document.getElementById('input-ig-barber').value.trim();
    var keahlian = document.getElementById('input-keahlian-barber').value.trim();
    var desc = document.getElementById('input-desc-barber').value.trim();

    // Validasi sederhana
    if (nama === '') {
        alert('Nama barber tidak boleh kosong!');
        return;
    }

    // Buat ID unik untuk barber baru
    var idBaru = 'barber-' + Date.now();

    // Buat elemen kartu barber baru
    var kartuBaru = document.createElement('div');
    kartuBaru.className = 'cont-perorang';
    kartuBaru.id = idBaru;

    kartuBaru.innerHTML =
        '<div class="img-pen">' +
            '<img src="img/mount-batur.jpg" alt="' + nama + '">' +
        '</div>' +
        '<div class="p-pen">' +
            '<h2 class="nama-barber">' + nama.toUpperCase() + '</h2>' +
            '<div class="contact-barber">' +
                '<div class="row-contact">' +
                    '<ion-icon name="mail-outline" class="icon-contact"></ion-icon>' +
                    '<a href="">' + (email || '-') + '</a>' +
                '</div>' +
                '<div class="row-contact">' +
                    '<ion-icon name="logo-instagram" class="icon-contact"></ion-icon>' +
                    '<a href="">' + (ig || '-') + '</a>' +
                '</div>' +
            '</div>' +
            '<p class="desc-barber">' + (desc || keahlian || 'Barber profesional Crown Barbershop.') + '</p>' +
            '<div class="status-barber">' +
                '<span class="status accepted">Aktif</span>' +
            '</div>' +
            '<div class="barber-aksi">' +
                '<button class="btn-aksi btn-hapus" onclick="hapusBarber(\'' + idBaru + '\')">' +
                    '<ion-icon name="trash-outline"></ion-icon> Hapus' +
                '</button>' +
            '</div>' +
        '</div>';

    // Tambahkan ke daftar barber
    document.getElementById('daftar-barber').appendChild(kartuBaru);

    // Tutup form
    tutupFormBarber();

    alert('Barber berhasil ditambahkan!');
}


// ==========================
// BARBER - Hapus
// ==========================

function hapusBarber(id) {
    var konfirmasi = confirm('Apakah yakin ingin menghapus barber ini?');
    if (konfirmasi) {
        var elemen = document.getElementById(id);
        if (elemen) {
            elemen.remove();
        }
    }
}


// ==========================
// SERVICE - Form Tambah
// ==========================

function tampilkanFormService() {
    var form = document.getElementById('form-service');
    form.style.display = 'block';
    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function tutupFormService() {
    var form = document.getElementById('form-service');
    form.style.display = 'none';

    document.getElementById('input-nama-service').value = '';
    document.getElementById('input-harga-service').value = '';
    document.getElementById('input-durasi-service').value = '';
}

function simpanService() {
    var nama = document.getElementById('input-nama-service').value.trim();
    var kategori = document.getElementById('input-kategori-service').value;
    var harga = document.getElementById('input-harga-service').value.trim();
    var durasi = document.getElementById('input-durasi-service').value.trim();

    // Validasi
    if (nama === '' || harga === '') {
        alert('Nama layanan dan harga tidak boleh kosong!');
        return;
    }

    // Format harga ke Rupiah sederhana
    var hargaFormatted = 'Rp ' + parseInt(harga).toLocaleString('id-ID');
    var durasiText = durasi ? ('± ' + durasi + ' menit') : '-';

    // Buat baris tabel baru
    var barisBaru = document.createElement('tr');
    barisBaru.innerHTML =
        '<td>' + nama + '</td>' +
        '<td>' + hargaFormatted + '</td>' +
        '<td>' + durasiText + '</td>' +
        '<td><button class="btn-aksi btn-hapus" onclick="hapusBaris(this)">Hapus</button></td>';

    // Masukkan ke tbody yang sesuai kategori
    if (kategori === 'Anak-anak') {
        document.getElementById('tbody-anak').appendChild(barisBaru);
        // Aktifkan tab anak-anak
        document.querySelector('.tab-btn:nth-child(2)').click();
    } else {
        document.getElementById('tbody-dewasa').appendChild(barisBaru);
        // Aktifkan tab dewasa
        document.querySelector('.tab-btn:nth-child(1)').click();
    }

    tutupFormService();
    alert('Layanan berhasil ditambahkan!');
}


// ==========================
// SERVICE - Tab
// ==========================

function gantiTab(tombol, targetId) {
    // Hapus active dari semua tab btn dan tab content
    document.querySelectorAll('.tab-btn').forEach(function(btn) {
        btn.classList.remove('active');
    });
    document.querySelectorAll('.tab-content').forEach(function(content) {
        content.classList.remove('active');
    });

    // Tambah active ke yang diklik
    tombol.classList.add('active');
    document.getElementById(targetId).classList.add('active');
}


// ==========================
// SERVICE - Hapus Baris
// ==========================

function hapusBaris(tombol) {
    var konfirmasi = confirm('Hapus layanan ini?');
    if (konfirmasi) {
        tombol.closest('tr').remove();
    }
}


// ==========================
// GALLERY - Upload & Hapus
// ==========================

function uploadFoto(input) {
    var files = input.files;

    for (var i = 0; i < files.length; i++) {
        var file = files[i];

        // Hanya terima gambar
        if (!file.type.startsWith('image/')) {
            continue;
        }

        // Buat URL sementara untuk preview
        var url = URL.createObjectURL(file);

        // Buat elemen gallery item baru
        var item = document.createElement('div');
        item.className = 'gallery-item';
        item.innerHTML =
            '<img src="' + url + '" alt="Foto baru">' +
            '<div class="gallery-overlay">' +
                '<button class="btn-hapus-foto" onclick="hapusFoto(this)">' +
                    '<ion-icon name="trash-outline"></ion-icon>' +
                '</button>' +
            '</div>';

        document.getElementById('gallery-admin').appendChild(item);
    }
}

function hapusFoto(tombol) {
    var konfirmasi = confirm('Hapus foto ini?');
    if (konfirmasi) {
        tombol.closest('.gallery-item').remove();
    }
}


// ==========================
// SETTINGS - Simpan
// ==========================

function simpanSettings() {
    // Di project nyata ini akan dikirim ke backend
    // Untuk sekarang cukup tampilkan notifikasi
    alert('Pengaturan berhasil disimpan!');
}
