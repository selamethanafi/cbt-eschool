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
        // Fungsi untuk toggle navigasi soal
        function toggleQuestionNav() {
            const navContainer = document.querySelector('.question-nav-container');
            const icon = document.getElementById('navToggleIcon');
            
            navContainer.classList.toggle('show');
            icon.innerHTML = navContainer.classList.contains('show') ?
                '<i class="fas fa-chevron-up"></i>' :
                '<i class="fas fa-chevron-down"></i>';
        }

        // Timer function dengan auto-save
        function startTimer(duration, display) {
            var timer = duration, hours, minutes, seconds;
            var lastSaveTime = 0;
            
            var timerInterval = setInterval(function () {
                hours = parseInt(timer / 3600, 10);
                minutes = parseInt((timer % 3600) / 60, 10);
                seconds = parseInt(timer % 60, 10);

                hours = hours < 10 ? "0" + hours : hours;
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = hours + ":" + minutes + ":" + seconds;

                // Auto-save setiap 1 menit (60 detik)
                var currentTime = duration - timer;
                if (currentTime - lastSaveTime >= 60 && currentTime > 0) {
                    lastSaveTime = currentTime;
                    autoSaveJawaban();
                }

                if (--timer < 0) {
                    clearInterval(timerInterval);
                    document.getElementById('examForm').submit();
                }
            }, 1000);
        }

        // Fungsi untuk auto-save jawaban
        function autoSaveJawaban() {
            const formData = new FormData(document.getElementById('examForm'));
            formData.append('auto_save', 'true');
            
            fetch('simpan_jawaban.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    showAutoSaveStatus();
                    updateAnsweredQuestions();
                } else {
                    console.error('Gagal menyimpan jawaban otomatis');
                }
            })
            .catch(error => {
                console.error('Error saat auto-save:', error);
            });
        }

        // Tampilkan notifikasi auto-save
        function showAutoSaveStatus() {
            const statusElement = document.getElementById('autoSaveStatus');
            statusElement.style.display = 'block';
            setTimeout(() => {
                statusElement.style.display = 'none';
            }, 3000);
        }

        // Update tampilan soal yang sudah dijawab
        function updateAnsweredQuestions() {
            const questionButtons = document.querySelectorAll('.question-nav-btn');
            questionButtons.forEach(button => {
                const questionNumber = button.textContent.trim();
                const answerInputs = document.querySelectorAll(`input[name^="jawaban"], textarea[name="jawaban"], select[name^="jawaban"]`);
                let hasAnswer = false;
                
                answerInputs.forEach(input => {
                    if ((input.type === 'radio' || input.type === 'checkbox') && input.checked) {
                        hasAnswer = true;
                    } else if ((input.type === 'text' || input.tagName === 'TEXTAREA' || input.tagName === 'SELECT') && input.value) {
                        hasAnswer = true;
                    }
                });
                
                if (hasAnswer) {
                    button.classList.add('answered');
                } else {
                    button.classList.remove('answered');
                }
            });
        }

        // Start timer when DOM is ready
        window.addEventListener('DOMContentLoaded', function () {
            var duration = 3600; // 1 hour in seconds
            var display = document.querySelector('#time');
            if (display) startTimer(duration, display);
            
            // Update answered questions on load
            updateAnsweredQuestions();
        });

        // Handle navigation
        document.querySelectorAll('.btn-navigate').forEach(button => {
            button.addEventListener('click', function (e) {
                if (this.tagName === 'A') {
                    e.preventDefault();
                    document.getElementById('loadingOverlay').style.display = 'flex';
                    
                    // Langsung navigasi tanpa menyimpan
                    setTimeout(() => {
                        window.location.href = this.getAttribute('href');
                    }, 300);
                }
            });
        });

        // Handle form submit untuk selesai ujian
        document.getElementById('examForm').addEventListener('submit', function(e) {
            const submitButton = e.submitter;
            if (submitButton.name === 'selesai') {
                e.preventDefault();
                
                // Tampilkan konfirmasi sebelum submit akhir
                Swal.fire({
                    title: 'Selesai Ujian?',
                    text: "Anda tidak dapat mengubah jawaban setelah mengirim!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Selesai!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit form secara normal
                        this.submit();
                    }
                });
            }
        });

        // Hide loading spinner after full load
        window.addEventListener('load', function () {
            document.getElementById('loadingOverlay').style.display = 'none';
        });

        // Update answered questions when inputs change
        document.querySelectorAll('input, textarea, select').forEach(input => {
            input.addEventListener('change', updateAnsweredQuestions);
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
</script>
<script>
function getJawabanDariForm() {
    const totalSoal = parseInt(document.getElementById('total_soal').value);
    let jawaban = [];

    for (let i = 1; i <= totalSoal; i++) {
        let elemen = document.querySelectorAll(`[name^="jawaban_${i}"]`);

        if (elemen.length === 1) {
            // Input tunggal: text atau radio
            const el = elemen[0];
            if (el.type === 'radio') {
                const selected = document.querySelector(`[name="jawaban_${i}"]:checked`);
                jawaban.push(selected ? selected.value : '');
            } else {
                jawaban.push(el.value.trim());
            }
        } else if (elemen.length > 1) {
            // Checkbox atau matching
            let nilai = [];
            elemen.forEach(e => {
                if ((e.type === 'checkbox' && e.checked) || e.tagName.toLowerCase() === 'select' || e.type === 'text') {
                    if (e.value.trim() !== '') nilai.push(e.value.trim());
                }
            });
            jawaban.push(nilai.length > 0 ? nilai.join(e.type === 'checkbox' ? ',' : '|') : '');
        } else {
            jawaban.push(''); // default kosong
        }
    }

    return jawaban;
}

function kirimJawaban() {
    const formData = new FormData();
    formData.append('id_siswa', document.getElementById('id_siswa').value);
    formData.append('nama_siswa', document.getElementById('nama_siswa').value);
    formData.append('kode_soal', document.getElementById('kode_soal').value);
    formData.append('total_soal', document.getElementById('total_soal').value);
    formData.append('waktu_sisa', document.getElementById('waktu_sisa').innerText || '00');

    const jawaban = getJawabanDariForm();
    jawaban.forEach((val, idx) => {
        formData.append(`jawaban[${idx}]`, val);
    });

    fetch('simpan_jawaban.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'sukses') {
            console.log('Jawaban berhasil disimpan otomatis.');
        } else {
            console.warn('Gagal simpan jawaban:', data.error || data.pesan);
        }
    });
}

// Jalankan simpan otomatis setiap 1 menit
setInterval(kirimJawaban, 60000); // 60.000 ms = 1 menit

var waktu = <?php echo $waktu_sisa; ?> * 60; // detik

var timer = setInterval(function() {
    var menit = Math.floor(waktu / 60);
    var detik = waktu % 60;
    document.getElementById("timer").innerHTML =
        (menit < 10 ? "0" + menit : menit) + ":" + (detik < 10 ? "0" + detik : detik);

    if (--waktu < 0) {
        clearInterval(timer);
        alert("Waktu habis! Jawaban akan dikirim.");
        window.location.href = "selesai.php"; // arahkan ke halaman selesai
    }

    // Simpan waktu_sisa ke server setiap 30 detik
    if (waktu % 30 === 0) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_waktu_sisa.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("id_siswa=<?php echo $id_siswa; ?>&kode_soal=<?php echo $kode_soal; ?>&waktu_sisa=" + Math.ceil(waktu / 60));
    }
}, 1000);
</script>