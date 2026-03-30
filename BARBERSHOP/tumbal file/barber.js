// ============================================================
//  barber.js
// ============================================================


// ==========================
// SERVICE - Toggle Kategori
// ==========================

const adultBtn      = document.getElementById('adultBtn');
const kidsBtn       = document.getElementById('kidsBtn');
const adultCategory = document.getElementById('adultCategory');
const kidsCategory  = document.getElementById('kidsCategory');
const caption       = document.querySelector('.caption');
const captionTitle  = document.getElementById('captionTitle');
const captionText   = document.getElementById('captionText');

// Awal state
adultCategory.style.display = 'none';
kidsCategory.style.display  = 'none';

function resetIcons() {
    adultBtn.classList.remove('icon-active');
    kidsBtn.classList.remove('icon-active');
}

function showCategory(showElem, hideElem) {
    hideElem.style.display = 'none';
    hideElem.classList.remove('show');
    showElem.style.display = 'block';
    setTimeout(() => showElem.classList.add('show'), 20);
}

function updateCaption(title, text) {
    caption.classList.add('fade');
    setTimeout(() => {
        captionTitle.textContent = title;
        captionText.textContent  = text;
        caption.classList.remove('fade');
    }, 250);
}

adultBtn.addEventListener('click', () => {
    showCategory(adultCategory, kidsCategory);
    updateCaption('Daftar Harga Dewasa', 'Berikut adalah harga layanan untuk kategori dewasa.');
    resetIcons();
    adultBtn.classList.add('icon-active');
});

kidsBtn.addEventListener('click', () => {
    showCategory(kidsCategory, adultCategory);
    updateCaption('Daftar Harga Anak-anak', 'Berikut adalah harga layanan untuk kategori anak-anak.');
    resetIcons();
    kidsBtn.classList.add('icon-active');
});


// ==========================
// NAVBAR - Cek Status Login
// ==========================
// Memanggil session_info.php yang mengembalikan data session user.
// Hasilnya dipakai untuk mengubah tampilan ikon person di navbar:
//   - Belum login  → ikon biasa, link ke login.html
//   - Sudah login  → tampil nama + dropdown (Profil & Logout)

async function updateNavbar() {
    try {
        const res  = await fetch('../api/session_info.php');
        const data = await res.json();

        const navAuth = document.getElementById('nav-auth');
        if (!navAuth) return;

        if (data.sudahLogin) {
            // Render dropdown nama user
            navAuth.innerHTML = `
                <div class="nav-user" id="nav-user-toggle">
                    <ion-icon name="person-circle-outline"></ion-icon>
                    <span class="nav-user-nama">${data.nama}</span>
                    <ion-icon name="chevron-down-outline" class="nav-chevron"></ion-icon>
                </div>
                <div class="nav-dropdown" id="nav-dropdown">
                    ${data.role === 'admin'
                        ? `<a href="/admin/admin.php" class="nav-dropdown-item">
                               <ion-icon name="grid-outline"></ion-icon> Admin Panel
                           </a>`
                        : ''}
                    <button class="nav-dropdown-item nav-dropdown-item--logout" id="btn-logout">
                        <ion-icon name="log-out-outline"></ion-icon> Logout
                    </button>
                </div>
            `;

            // Toggle dropdown saat diklik
            document.getElementById('nav-user-toggle').addEventListener('click', (e) => {
                e.stopPropagation();
                document.getElementById('nav-dropdown').classList.toggle('show');
            });

            // Tutup dropdown kalau klik di luar
            document.addEventListener('click', () => {
                const dd = document.getElementById('nav-dropdown');
                if (dd) dd.classList.remove('show');
            });

            // Tombol logout
            document.getElementById('btn-logout').addEventListener('click', async () => {
                await fetch('../api/auth.php', {
                    method : 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body   : JSON.stringify({ action: 'logout' })
                });
                window.location.reload();
            });

        } else {
            // Belum login → ikon link ke halaman login
            navAuth.innerHTML = `
                <a href="../login-register/login.html">
                    <ion-icon name="person-circle-outline"></ion-icon>
                </a>
            `;
        }

    } catch (err) {
        // Kalau fetch gagal (misal server mati), biarkan navbar default
        console.warn('Gagal cek session:', err);
    }
}

// Jalankan saat halaman siap
document.addEventListener('DOMContentLoaded', updateNavbar);
