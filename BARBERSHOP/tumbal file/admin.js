// ============================================================
//  admin.js
// ============================================================


// ==========================
// NAVIGASI HALAMAN
// ==========================

const navLinks    = document.querySelectorAll('.nav-link');
const pages       = document.querySelectorAll('.page');
const topbarTitle = document.getElementById('topbar-title');

navLinks.forEach(function(link) {
    link.addEventListener('click', function(e) {
        e.preventDefault();

        const targetPage = this.getAttribute('data-page');

        navLinks.forEach(function(l) { l.classList.remove('active'); });
        pages.forEach(function(p)    { p.classList.remove('active'); });

        this.classList.add('active');
        document.getElementById('page-' + targetPage).classList.add('active');

        const judul = {
            dashboard : 'Dashboard',
            booking   : 'Booking',
            barber    : 'Barber',
            service   : 'Service',
            gallery   : 'Gallery',
            settings  : 'Settings'
        };
        topbarTitle.textContent = judul[targetPage];
    });
});


// ==========================
// LOGOUT ADMIN
// ==========================

const btnLogoutAdmin = document.getElementById('btn-logout-admin');
if (btnLogoutAdmin) {
    btnLogoutAdmin.addEventListener('click', async function() {
        if (!confirm('Yakin ingin logout?')) return;

        try {
            await fetch('/api/auth.php', {
                method : 'POST',
                headers: { 'Content-Type': 'application/json' },
                body   : JSON.stringify({ action: 'logout' })
            });
        } catch (e) { /* tetap redirect */ }

        window.location.href = '../pages/barber.php';
    });
}


// ==========================
// BOOKING - Filter & Cari
// ==========================

const inputCariBooking = document.getElementById('input-cari-booking');
const filterStatus     = document.getElementById('filter-status');

function filterBooking() {
    const keyword       = inputCariBooking.value.toLowerCase();
    const statusDipilih = filterStatus.value;
    const rows          = document.querySelectorAll('#tbody-booking tr');

    rows.forEach(function(row) {
        const nama       = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const status     = row.getAttribute('data-status');
        const cocoNama   = nama.includes(keyword);
        const cocoStatus = (statusDipilih === 'semua') || (status === statusDipilih);

        row.style.display = (cocoNama && cocoStatus) ? '' : 'none';
    });
}

if (inputCariBooking) inputCariBooking.addEventListener('input', filterBooking);
if (filterStatus)     filterStatus.addEventListener('change', filterBooking);


// ==========================
// BOOKING - Ubah Status
// ==========================

function ubahStatus(tombol, statusBaru) {
    const row        = tombol.closest('tr');
    const spanStatus = row.querySelector('.status');

    spanStatus.className   = 'status ' + statusBaru;
    const teksStatus = {
        accepted  : 'Accepted',
        completed : 'Completed',
        canceled  : 'Canceled',
        pending   : 'Pending'
    };
    spanStatus.textContent = teksStatus[statusBaru];
    row.setAttribute('data-status', statusBaru);

    const kolomAksi = row.querySelector('td:last-child');
    if (statusBaru === 'accepted') {
        kolomAksi.innerHTML =
            '<button class="btn-aksi btn-edit" onclick="ubahStatus(this,\'completed\')">Selesai</button>' +
            '<button class="btn-aksi btn-hapus" onclick="ubahStatus(this,\'canceled\')">Batal</button>';
    } else if (statusBaru === 'completed') {
        kolomAksi.innerHTML =
            '<button class="btn-aksi btn-hapus" onclick="ubahStatus(this,\'canceled\')">Batal</button>';
    } else if (statusBaru === 'canceled') {
        kolomAksi.innerHTML =
            '<button class="btn-aksi btn-edit" onclick="ubahStatus(this,\'accepted\')">Aktifkan</button>';
    }
}


// ==========================
// BARBER - Form Tambah
// ==========================

function tampilkanFormBarber() {
    const form = document.getElementById('form-barber');
    form.style.display = 'block';
    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function tutupFormBarber() {
    document.getElementById('form-barber').style.display   = 'none';
    document.getElementById('input-nama-barber').value     = '';
    document.getElementById('input-email-barber').value    = '';
    document.getElementById('input-ig-barber').value       = '';
    document.getElementById('input-keahlian-barber').value = '';
    document.getElementById('input-desc-barber').value     = '';
}

function simpanBarber() {
    const nama     = document.getElementById('input-nama-barber').value.trim();
    const email    = document.getElementById('input-email-barber').value.trim();
    const ig       = document.getElementById('input-ig-barber').value.trim();
    const keahlian = document.getElementById('input-keahlian-barber').value.trim();
    const desc     = document.getElementById('input-desc-barber').value.trim();

    if (!nama) { alert('Nama barber tidak boleh kosong!'); return; }

    const idBaru    = 'barber-' + Date.now();
    const kartuBaru = document.createElement('div');
    kartuBaru.className = 'cont-perorang';
    kartuBaru.id        = idBaru;

    kartuBaru.innerHTML =
        '<div class="img-pen"><img src="../assets/img/mount-batur.jpg" alt="' + nama + '"></div>' +
        '<div class="p-pen">' +
            '<h2 class="nama-barber">' + nama.toUpperCase() + '</h2>' +
            '<div class="contact-barber">' +
                '<div class="row-contact"><ion-icon name="mail-outline" class="icon-contact"></ion-icon><a href="">' + (email || '-') + '</a></div>' +
                '<div class="row-contact"><ion-icon name="logo-instagram" class="icon-contact"></ion-icon><a href="">' + (ig || '-') + '</a></div>' +
            '</div>' +
            '<p class="desc-barber">' + (desc || keahlian || 'Barber profesional Crown Barbershop.') + '</p>' +
            '<div class="status-barber"><span class="status accepted">Aktif</span></div>' +
            '<div class="barber-aksi"><button class="btn-aksi btn-hapus" onclick="hapusBarber(\'' + idBaru + '\')"><ion-icon name="trash-outline"></ion-icon> Hapus</button></div>' +
        '</div>';

    document.getElementById('daftar-barber').appendChild(kartuBaru);
    tutupFormBarber();
    alert('Barber berhasil ditambahkan!');
}

function hapusBarber(id) {
    if (confirm('Apakah yakin ingin menghapus barber ini?')) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }
}


// ==========================
// SERVICE - Form & Tab
// ==========================

function tampilkanFormService() {
    const form = document.getElementById('form-service');
    form.style.display = 'block';
    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function tutupFormService() {
    document.getElementById('form-service').style.display = 'none';
    document.getElementById('input-nama-service').value   = '';
    document.getElementById('input-harga-service').value  = '';
    document.getElementById('input-durasi-service').value = '';
}

function simpanService() {
    const nama     = document.getElementById('input-nama-service').value.trim();
    const kategori = document.getElementById('input-kategori-service').value;
    const harga    = document.getElementById('input-harga-service').value.trim();
    const durasi   = document.getElementById('input-durasi-service').value.trim();

    if (!nama || !harga) { alert('Nama layanan dan harga tidak boleh kosong!'); return; }

    const hargaFormatted = 'Rp ' + parseInt(harga).toLocaleString('id-ID');
    const durasiText     = durasi ? ('± ' + durasi + ' menit') : '-';

    const barisBaru = document.createElement('tr');
    barisBaru.innerHTML =
        '<td>' + nama + '</td>' +
        '<td>' + hargaFormatted + '</td>' +
        '<td>' + durasiText + '</td>' +
        '<td><button class="btn-aksi btn-hapus" onclick="hapusBaris(this)">Hapus</button></td>';

    if (kategori === 'Anak-anak') {
        document.getElementById('tbody-anak').appendChild(barisBaru);
        document.querySelector('.tab-btn:nth-child(2)').click();
    } else {
        document.getElementById('tbody-dewasa').appendChild(barisBaru);
        document.querySelector('.tab-btn:nth-child(1)').click();
    }

    tutupFormService();
    alert('Layanan berhasil ditambahkan!');
}

function gantiTab(tombol, targetId) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    tombol.classList.add('active');
    document.getElementById(targetId).classList.add('active');
}

function hapusBaris(tombol) {
    if (confirm('Hapus layanan ini?')) tombol.closest('tr').remove();
}


// ==========================
// GALLERY - Upload & Hapus
// ==========================

function uploadFoto(input) {
    Array.from(input.files).forEach(file => {
        if (!file.type.startsWith('image/')) return;

        const url  = URL.createObjectURL(file);
        const item = document.createElement('div');
        item.className = 'gallery-item';
        item.innerHTML =
            '<img src="' + url + '" alt="Foto baru">' +
            '<div class="gallery-overlay">' +
                '<button class="btn-hapus-foto" onclick="hapusFoto(this)"><ion-icon name="trash-outline"></ion-icon></button>' +
            '</div>';
        document.getElementById('gallery-admin').appendChild(item);
    });
}

function hapusFoto(tombol) {
    if (confirm('Hapus foto ini?')) tombol.closest('.gallery-item').remove();
}


// ==========================
// SETTINGS - Load dari DB
// ==========================
// Prefill semua input form settings dengan data terbaru dari DB
// dipanggil otomatis saat halaman admin pertama kali dibuka.

async function loadSettings() {
    try {
        const res  = await fetch('/api/settings.php');
        const data = await res.json();
        if (!data.success) return;

        const s = data.data;

        // Map id elemen → kunci di tabel settings
        const peta = {
            'set-nama'    : 'nama_barbershop',
            'set-tagline' : 'tagline',
            'set-alamat'  : 'alamat',
            'set-telp'    : 'telepon',
            'set-email'   : 'email',
            'set-about1'  : 'about_p1',
            'set-about2'  : 'about_p2',
            'set-about3'  : 'about_p3',
            'set-ig'      : 'instagram',
            'set-fb'      : 'facebook',
            'set-tiktok'  : 'tiktok',
        };

        Object.entries(peta).forEach(([id, kunci]) => {
            const el = document.getElementById(id);
            if (el && s[kunci] !== undefined) el.value = s[kunci];
        });

        // Jam operasional — urutan sesuai urutan di HTML
        const hariUrut  = ['senin','selasa','rabu','kamis','jumat','sabtu','minggu'];
        const jamInputs = document.querySelectorAll('.jam-input');
        jamInputs.forEach((input, i) => {
            const kunci = 'jam_' + hariUrut[i];
            if (s[kunci] !== undefined) input.value = s[kunci];
        });

    } catch (err) {
        console.warn('Gagal load settings:', err);
    }
}


// ==========================
// SETTINGS - Simpan ke DB
// ==========================

async function simpanSettings() {
    const btnSimpan = document.querySelector('.btn-simpan-settings');
    const teksAwal  = btnSimpan.innerHTML;

    btnSimpan.disabled  = true;
    btnSimpan.innerHTML = '<ion-icon name="sync-outline"></ion-icon> Menyimpan...';

    // Jam operasional
    const hariUrut  = ['senin','selasa','rabu','kamis','jumat','sabtu','minggu'];
    const jamInputs = document.querySelectorAll('.jam-input');
    const jamData   = {};
    jamInputs.forEach((input, i) => {
        jamData['jam_' + hariUrut[i]] = input.value.trim();
    });

    const payload = {
        action          : 'simpan',
        nama_barbershop : document.getElementById('set-nama')?.value.trim()    || '',
        tagline         : document.getElementById('set-tagline')?.value.trim() || '',
        alamat          : document.getElementById('set-alamat')?.value.trim()  || '',
        telepon         : document.getElementById('set-telp')?.value.trim()    || '',
        email           : document.getElementById('set-email')?.value.trim()   || '',
        about_p1        : document.getElementById('set-about1')?.value.trim()  || '',
        about_p2        : document.getElementById('set-about2')?.value.trim()  || '',
        about_p3        : document.getElementById('set-about3')?.value.trim()  || '',
        instagram       : document.getElementById('set-ig')?.value.trim()      || '',
        facebook        : document.getElementById('set-fb')?.value.trim()      || '',
        tiktok          : document.getElementById('set-tiktok')?.value.trim()  || '',
        ...jamData,
    };

    try {
        const res  = await fetch('../api/settings.php', {
            method : 'POST',
            headers: { 'Content-Type': 'application/json' },
            body   : JSON.stringify(payload)
        });
        const data = await res.json();

        if (data.success) {
            // Feedback visual: tombol berubah jadi centang sebentar
            btnSimpan.innerHTML = '<ion-icon name="checkmark-outline"></ion-icon> Tersimpan!';
            setTimeout(() => {
                btnSimpan.disabled  = false;
                btnSimpan.innerHTML = teksAwal;
            }, 2000);
        } else {
            alert('Gagal menyimpan: ' + data.message);
            btnSimpan.disabled  = false;
            btnSimpan.innerHTML = teksAwal;
        }

    } catch (err) {
        alert('Terjadi kesalahan koneksi.');
        btnSimpan.disabled  = false;
        btnSimpan.innerHTML = teksAwal;
    }
}


// ==========================
// INIT
// ==========================

document.addEventListener('DOMContentLoaded', loadSettings);
