<script>
    setInterval(function() {
        fetch('update_activity.php'); // Kirim ping setiap 60 detik
    }, 60000); 

    // Fungsi untuk mengirimkan URL halaman yang dikunjungi ke server
    function sendPageVisitToServer() {
        var pageURL = window.location.href; // Mendapatkan URL halaman saat ini
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_activity.php", true); // Mengirimkan ke update_activity.php
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("page_url=" + encodeURIComponent(pageURL)); // Kirim page_url
    }

    // Kirim URL saat halaman dimuat pertama kali
    window.addEventListener('load', function() {
        sendPageVisitToServer(); // Mengirimkan page_url saat halaman pertama kali dimuat
    });

    // Kirim URL saat ada perubahan halaman (untuk aplikasi berbasis AJAX atau SPA)
    window.addEventListener('popstate', function() {
        sendPageVisitToServer(); // Mengirimkan page_url jika ada perubahan halaman
    });
</script>
