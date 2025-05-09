<footer class="footer mt-auto py-3 bg-dark">
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
        </script>