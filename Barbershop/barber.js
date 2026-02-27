const adultBtn = document.getElementById('adultBtn');
const kidsBtn = document.getElementById('kidsBtn');

const adultCategory = document.getElementById('adultCategory');
const kidsCategory = document.getElementById('kidsCategory');

const caption = document.querySelector(".caption");
const captionTitle = document.getElementById('captionTitle');
const captionText = document.getElementById('captionText');



// Awal state
adultCategory.style.display = "none";
kidsCategory.style.display = "none";

// Reset icon state
function resetIcons() {
    adultBtn.classList.remove("icon-active");
    kidsBtn.classList.remove("icon-active");
}

// Tampilkan kategori dengan animasi
function showCategory(showElem, hideElem) {
    hideElem.style.display = "none";
    hideElem.classList.remove("show");

    showElem.style.display = "block";

    setTimeout(() => {
        showElem.classList.add("show");
    }, 20);
}

// Animasi caption elegan
function updateCaption(title, text) {
    caption.classList.add("fade");

    setTimeout(() => {
        captionTitle.textContent = title;
        captionText.textContent = text;
        caption.classList.remove("fade");
    }, 250);
}

// Tombol Dewasa
adultBtn.addEventListener('click', () => {
    showCategory(adultCategory, kidsCategory);
    updateCaption("Daftar Harga Dewasa", "Berikut adalah harga layanan untuk kategori dewasa.");

    resetIcons();
    adultBtn.classList.add("icon-active");
});

// Tombol Anak-anak
kidsBtn.addEventListener('click', () => {
    showCategory(kidsCategory, adultCategory);
    updateCaption("Daftar Harga Anak-anak", "Berikut adalah harga layanan untuk kategori anak-anak.");

    resetIcons();
    kidsBtn.classList.add("icon-active");
});