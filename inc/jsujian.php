<footer class="footer mt-auto py-3 bg-dark sticky-bottom">
                                                    <div class="container-fluid">
                                                        <div class="row text-grey">
                                                            <div class="col-6 text-start">
                                                                <p class="mb-0">
                                                                <a href="#" id="enc" style="color:grey;"></a>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </footer>
                                                <script src="../assets/adminkit/static/js/app.js"></script>
<script src="../assets/js/jquery-3.6.0.min.js"></script>
<script src="../assets/js/sweetalert.js"></script>
<script src="../assets/datatables/datatables.js"></script>
<audio id="notif-sound" src="../assets/notif.mp3" preload="auto"></audio>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Ambil semua elemen dengan data-bs-toggle="collapse"
            document.querySelectorAll('[data-bs-toggle="collapse"]')
                .forEach(item => {
                    item.addEventListener('click', function() {
                        // Ambil elemen target collapse berdasarkan href
                        var target = document.querySelector(item.getAttribute('href'));
                        var chevronIcon = item.querySelector('.fa-chevron-down');

                        // Menambahkan event listener untuk ketika submenu dibuka
                        target.addEventListener('shown.bs.collapse', function () {
                            if (chevronIcon) {
                                chevronIcon.classList.remove('fa-chevron-down');
                                chevronIcon.classList.add('fa-chevron-up');
                            }
                        });

                        // Menambahkan event listener untuk ketika submenu ditutup
                        target.addEventListener('hidden.bs.collapse', function () {
                            if (chevronIcon) {
                                chevronIcon.classList.remove('fa-chevron-up');
                                chevronIcon.classList.add('fa-chevron-down');
                            }
                        });
                    });
                });
        });

        document.addEventListener("DOMContentLoaded", function() {
        var base64Text = "<?php echo $encryptedText; ?>"; 
            if(base64Text) {
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

        document.addEventListener('DOMContentLoaded', function () {
        const sound = document.getElementById('notif-sound');  // Elemen suara
        const toasts = document.querySelectorAll('#toast-container .toast');  // Semua toast

        // Daftar class background yang tersedia di Bootstrap
        const bgClasses = [
            'bg-primary', 'bg-secondary', 'bg-success', 'bg-danger',
            'bg-warning', 'bg-info', 'bg-dark'
        ];

        // Fungsi untuk memilih class acak dari daftar
        function getRandomBgClass() {
            const randomIndex = Math.floor(Math.random() * bgClasses.length);
            return bgClasses[randomIndex];
        }

        // Cek apakah toast sudah pernah ditampilkan di session
        const toastShown = sessionStorage.getItem('toastDisplayed');

        if (!toastShown) {
            // Tambahkan event listener untuk klik pertama di halaman
            const onClick = () => {
                toasts.forEach((toastEl, index) => {
                    // Pilih class background acak dan hapus class sebelumnya
                    toastEl.classList.remove(...bgClasses);  // Hapus class lama
                    toastEl.classList.add(getRandomBgClass());  // Tambahkan class acak
                    toastEl.style.borderRadius = '20px';

                    // Delay pertama 500ms, selanjutnya kelipatan 3 detik
                    const delayTime = index === 0 ? 500 : (index * 3000);

                    setTimeout(() => {
                        toastEl.classList.remove('opacity-0');  
                        const toast = new bootstrap.Toast(toastEl);
                        toast.show();

                        // Mainkan suara
                        if (sound) {
                            sound.currentTime = 0;
                            sound.play().catch(() => {});
                        }
                    }, delayTime);
                });

                // Simpan flag agar tidak ditampilkan lagi selama sesi ini
                sessionStorage.setItem('toastDisplayed', 'true');

                // Hapus event listener agar tidak dipanggil lagi
                document.body.removeEventListener('click', onClick);
            };

            document.body.addEventListener('click', onClick);
        }
    });
</script>
<script>
document.querySelectorAll('.btnLogout').forEach(function(el) {
    el.addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Yakin ingin logout?',
            text: "Anda akan keluar dari sesi ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'logout.php';
            }
        });
    });
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    // === TIMER FUNCTION ===
    function startTimer(duration, display) {
        var timer = duration, hours, minutes, seconds;
        setInterval(function () {
            hours = parseInt(timer / 3600, 10);
            minutes = parseInt((timer % 3600) / 60, 10);
            seconds = parseInt(timer % 60, 10);

            hours = hours < 10 ? "0" + hours : hours;
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.textContent = hours + ":" + minutes + ":" + seconds;

            if (--timer < 0) {
                document.getElementById('examForm').submit();
            }
        }, 1000);
    }

    // Mulai timer saat DOM siap
    var duration = 3600; // 1 jam
    var display = document.querySelector('#time');
    if (display) startTimer(duration, display);

    // === TOGGLE NAVIGATION ===
    function toggleQuestionNav() {
        const nav = document.getElementById('questionNav');
        const icon = document.getElementById('navToggleIcon');
        
        nav.classList.toggle('collapsed');
        icon.innerHTML = nav.classList.contains('collapsed') ?
            '<i class="fas fa-chevron-up"></i>' :
            '<i class="fas fa-chevron-down"></i>';
        
        localStorage.setItem('navCollapsed', nav.classList.contains('collapsed'));
    }
    window.toggleQuestionNav = toggleQuestionNav; // Expose function if used in onclick

    const nav = document.getElementById('questionNav');
    const icon = document.getElementById('navToggleIcon');
    const isCollapsed = localStorage.getItem('navCollapsed') === 'true';

    if (nav && icon) {
        if (isCollapsed) {
            nav.classList.add('collapsed');
            icon.innerHTML = '<i class="fas fa-chevron-up"></i>';
        } else {
            nav.classList.remove('collapsed');
            icon.innerHTML = '<i class="fas fa-chevron-down"></i>';
        }
    }

    // === NAVBAR SCROLL EFFECT ===
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 10) {
                navbar.classList.add('bg-white', 'shadow');
            } else {
                navbar.classList.remove('bg-white', 'shadow');
            }
        });
    }

    // === HANDLE BUTTON CLICK + AJAX SAVE ===
    const navButtons = document.querySelectorAll('.btn-navigate, .question-nav-btn');
    navButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            document.getElementById('loadingOverlay').style.display = 'flex';

            const formData = new FormData(document.getElementById('examForm'));
            const spinnerStartTime = Date.now();
            const minDisplayTime = 100;

            fetch('simpan_jawaban.php', {
                method: 'POST',
                body: formData
            }).then(response => {
                const elapsed = Date.now() - spinnerStartTime;
                const remainingTime = Math.max(0, minDisplayTime - elapsed);
                setTimeout(() => {
                    window.location.href = this.getAttribute('href');
                }, remainingTime);
            }).catch(error => {
                console.error('Error:', error);
                document.getElementById('loadingOverlay').style.display = 'none';
                alert('Gagal menyimpan jawaban. Silakan coba lagi.');
            });
        });
    });

    // === PAGE VISIT TRACKING ===
    function sendPageVisitToServer() {
        var pageURL = window.location.href;
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_activity.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("page_url=" + encodeURIComponent(pageURL));
    }

    sendPageVisitToServer(); // on first load
    window.addEventListener('popstate', sendPageVisitToServer); // on navigation (SPA)

    // === ACTIVITY PING PER 60 DETIK ===
    setInterval(function () {
        fetch('update_activity.php');
    }, 60000);
});

// === HIDE LOADING SPINNER SETELAH FULL LOAD ===
window.addEventListener('load', function () {
    document.getElementById('loadingOverlay').style.display = 'none';
});
</script>