    <script>
// Timer Logic
let waktu = <?= $waktu_sisa > 0 ? ($waktu_sisa * 60) : 3600 ?>;
let soalAktif = 0;
const totalSoal = <?= count($soal) ?>;

// Tampilkan loading overlay saat pertama kali load
document.getElementById('loadingOverlay').style.display = 'flex';
setTimeout(() => {
    document.getElementById('loadingOverlay').style.display = 'none';
}, 500);

function updateTimer() {
    let menit = Math.floor(waktu / 60);
    let detik = waktu % 60;
    document.getElementById('timer').innerText =
        `${menit.toString().padStart(2, '0')}:${detik.toString().padStart(2, '0')}`;
    waktu--;

    if (waktu < 0) {
        document.getElementById('formUjian').submit();
    }
}

function updateNavigationButtons() {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    prevBtn.style.display = soalAktif > 0 ? 'block' : 'none';

    if (soalAktif < totalSoal - 1) {
        nextBtn.style.display = 'block';
        submitBtn.style.display = 'none';
    } else {
        nextBtn.style.display = 'none';
        submitBtn.style.display = 'block';
    }
}

function tampilSoal(index) {
    document.querySelectorAll('.question-container').forEach(s => s.classList.remove('active'));
    const soal = document.getElementById('soal-' + index);
    if (soal) {
        soal.classList.add('active');
        soalAktif = index;

        // Tampilkan nomor urut (1, 2, 3, dst.)
        const currentNo = index + 1;
        document.getElementById('currentQuestionNumber').textContent = currentNo.toString().padStart(2, '0');

        updateNavigationButtons();

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
}

function nextSoal() {
    if (soalAktif < totalSoal - 1) {
        tampilSoal(soalAktif + 1);
    }
}

function prevSoal() {
    if (soalAktif > 0) {
        tampilSoal(soalAktif - 1);
    }
}

// Auto save setiap interval tertentu
setInterval(() => {
    const form = document.getElementById('formUjian');
    const data = new FormData(form);
    data.append('waktu_sisa', Math.ceil(waktu / 60));

    fetch('autosave_jawaban.php', {
            method: 'POST',
            body: data
        })
        .then(res => res.text())
        .then(txt => console.log('Auto-saved:', txt));
}, syncInterval);

document.addEventListener("DOMContentLoaded", function() {
    var base64Text = "<?php echo $encryptedText; ?>";
    if (base64Text) {
        var decodedText = atob(base64Text);
        document.getElementById("enc").innerHTML = decodedText;
    }
});

function checkIfEncDeleted() {
    var encElement = document.getElementById("enc");

    if (!encElement) {
        window.location.href = "../error_page.php";
    }
}
setInterval(checkIfEncDeleted, 500);

// Fungsi toggle navigasi
function toggleNav() {
    const navContainer = document.querySelector('.question-nav-container');
    if (navContainer.style.display === 'none') {
        navContainer.style.display = 'block';
    } else {
        navContainer.style.display = 'none';
    }
}

function hideNav() {
    document.querySelector('.question-nav-container').style.display = 'none';
}

// Event listeners
document.getElementById('navToggle').addEventListener('click', toggleNav);
document.querySelector('.card-header button.close').addEventListener('click', hideNav);

// Update waktu_sisa setiap detik
setInterval(() => {
    document.getElementById('waktu_sisa').value = waktu;
}, 1000);

// Tangani klik tombol "Selesai"
document.getElementById('submitBtn').addEventListener('click', function(e) {
    e.preventDefault();

    const sisaDetik = parseInt(waktu) || 0;
    const menit = Math.floor(sisaDetik / 60);
    const detik = sisaDetik % 60;
    const formatWaktu = `${menit.toString().padStart(2, '0')}:${detik.toString().padStart(2, '0')}`;

    Swal.fire({
        title: 'Selesaikan Ujian?',
        html: `Sisa waktu Anda: <strong>${formatWaktu}</strong>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Selesai',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formUjian').submit();
        }
    });
});

// Panggil pertama kali untuk inisialisasi
updateNavigationButtons();
// Tampilkan soal pertama saat halaman dimuat
window.onload = function() {
    tampilSoal(0);
    setInterval(updateTimer, 1000);
    updateTimer();
};

// Fungsi untuk update status tombol navigasi
function updateNavButtons() {
    document.querySelectorAll('.nav-btn').forEach(btn => {
        const nomor = btn.getAttribute('data-nomor');
        const inputs = document.querySelectorAll(`[name^="jawaban[${nomor}]"]`);
        let answered = false;

        inputs.forEach(input => {
            if ((input.type === 'radio' || input.type === 'checkbox') && input.checked) {
                answered = true;
            } else if ((input.type === 'text' || input.tagName === 'TEXTAREA' || input.tagName ===
                    'SELECT') && input.value.trim() !== '') {
                answered = true;
            }
        });

        if (answered) {
            btn.classList.add('answered');
            btn.setAttribute('data-answered', 'true');
        } else {
            btn.classList.remove('answered');
            btn.setAttribute('data-answered', 'false');
        }
    });
}

// Panggil saat halaman load dan setiap ada perubahan jawaban
document.addEventListener('DOMContentLoaded', function() {
    updateNavButtons();

    // Deteksi perubahan jawaban
    document.querySelectorAll('input, textarea, select').forEach(el => {
        el.addEventListener('change', updateNavButtons);
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const images = document.querySelectorAll(".question-text img");

    images.forEach(img => {
        img.style.cursor = 'zoom-in';
        img.addEventListener("click", function() {
            const modal = document.getElementById("imageModal");
            const modalImg = document.getElementById("modalImage");
            modalImg.src = this.src;
            modal.classList.add("active");
        });
    });
});

function closeModal(event) {
    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalImage");

    // Supaya klik gambar tidak menutup modal
    if (event.target === modal || event.target.classList.contains("close-btn")) {
        modal.classList.remove("active");
        modalImg.src = "";
    }
}

const container = document.querySelector('.question-container');
const defaultFontSize = 16;
let currentFontSize = defaultFontSize;

function changeFontSize(delta) {
    currentFontSize += delta;
    if (currentFontSize < 10) currentFontSize = 10;
    if (currentFontSize > 30) currentFontSize = 30;
    container.style.fontSize = currentFontSize + 'px';
}

function resetFontSize() {
    currentFontSize = defaultFontSize;
    container.style.fontSize = defaultFontSize + 'px';
}
    </script>