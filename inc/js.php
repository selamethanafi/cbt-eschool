                                                <div id="toast-container">
                                                    <?php 
                                                        $ujian_terdekat = mysqli_query($koneksi, "SELECT * FROM soal WHERE tanggal > NOW() ORDER BY tanggal ASC");

                                                        if (mysqli_num_rows($ujian_terdekat) > 0):
                                                            mysqli_data_seek($ujian_terdekat, 0); 
                                                            while ($ujian = mysqli_fetch_assoc($ujian_terdekat)): 
                                                        ?>
                                                            <div class="toast align-items-center text-white bg-primary border-0 mb-2 opacity-0"
                                                                role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="6000">
                                                                <div class="d-flex">
                                                                    <div class="toast-body">
                                                                        <i class="far fa-calendar-check me-2"></i>
                                                                        Ujian <?php echo $ujian['kode_soal']; ?> dimulai pada <?php echo date('d M Y', strtotime($ujian['tanggal'])); ?>
                                                                    </div>
                                                                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                                                                        data-bs-dismiss="toast" aria-label="Close"></button>
                                                                </div>
                                                            </div>
                                                        <?php 
                                                            endwhile;
                                                        endif;
                                                        ?>
                                                </div>
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