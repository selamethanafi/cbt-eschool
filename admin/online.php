<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
$showResetButton = false;

// Cek apakah pengaturan login_ganda = 'blokir'
$cekPengaturan = mysqli_query($koneksi, "SELECT login_ganda FROM pengaturan LIMIT 1");
if (!$cekPengaturan) {
    die('Query pengaturan gagal: ' . mysqli_error($koneksi));
}
if ($cekPengaturan && mysqli_num_rows($cekPengaturan) > 0) {
    $dataPengaturan = mysqli_fetch_assoc($cekPengaturan);
    if ($dataPengaturan['login_ganda'] == 'blokir') {
        // Jika login_ganda = blokir, cek apakah ada siswa yang masih punya token_session
        $cekToken = mysqli_query($koneksi, "SELECT COUNT(*) as jumlah FROM siswa WHERE session_token != ''");
        if ($cekToken) {
            $row = mysqli_fetch_assoc($cekToken);
            if ($row['jumlah'] > 0) {
                $showResetButton = true;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Who's online</title>
    <?php include '../inc/css.php'; ?>
    <style>
        /* Custom styles */
        .force-logout {
            height: 24px;
            line-height: 1;
            padding: 0.15rem 0.5rem;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .badge {
            height: 24px;
            display: inline-flex;
            align-items: center;
            padding: 0.35em 0.65em;
        }
        
        @media (max-width: 576px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start !important;
            }
            .input-group {
                width: 100% !important;
                margin-top: 0.5rem;
            }
        }
        
        #searchInput {
            transition: all 0.3s ease;
        }
        
        .card-footer {
            min-height: 50px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <?php include 'sidebar.php'; ?>

        <div class="main">
            <?php include 'navbar.php'; ?>

            <main class="content">
                <div class="container-fluid p-0">

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <h5 class="card-title mb-0">Who's online</h5>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="input-group input-group-sm" style="width: 200px;">
                                            <input type="text" id="searchInput" class="form-control" placeholder="Cari siswa...">
                                            <button class="btn btn-outline-secondary" type="button" onclick="handleSearch()">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                        <small id="last-updated" class="text-muted"></small>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="card-container" class="row gy-3">

                                    
                                    </div>
                                    <nav>
                                        <ul class="pagination justify-content-center mt-3" id="pagination"></ul>
                                    </nav>
                                    <?php if ($showResetButton): ?>
                                        <button id="btn-reset-credential" class="btn btn-outline-danger">
                                            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> clear credential login siswa
                                        </button>
                                    <?php endif; ?>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>
    
    <?php include '../inc/js.php'; ?>
    <script>
    let currentPage = 1;
    const cardsPerPage = 12;
    let totalPages = 1;
    let currentSearch = '';
    let allOnlineStudents = [];
    let searchTimer;

    function renderCards(data) {
        if (data.length === 0) {
            $('#card-container').html(`
                <div class="col-12 text-center py-5"><i class="fa fa-user-slash fa-3x text-muted mb-3"></i><br>Tidak ada siswa online.</div>
            `);
            $('#pagination').hide();
            return;
        }
        
        $('#pagination').show();
        
        let html = '';
        data.forEach(function(row) {
            html += `
            <div class="col-6 col-lg-3 col-xl-2 col-sm-6 col-md-4">
                <div class="card text-dark bg-white border border-secondary">
                    <div class="card-header bg-light py-2" style="height:50px; display:flex; align-items:center;">
                        <h5 class="mb-1"><i class="fa fa-user-circle"></i> ${row[1]}</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-1">Kelas: ${row[2]} ${row[3]}</p>
                        <p class="mb-1"><i class="fa fa-clock"></i> ${row[4]}</p>
                        <p class="mb-1" style="font-size:12px;">${row[5]}</p>
                    </div>
                    <div class="card-footer bg-light py-2 d-flex justify-content-between align-items-center">
                   
                        <button class="btn btn-sm btn-outline-danger force-logout" style="border-radius:5px;"
                                data-id="${row[7]}" 
                                onclick="forceLogout('${row[7]}', '${row[1].replace(/'/g, "\\'")}')">
                            Force Logout
                        </button>
                         ${row[6]}   
                    </div>
                </div>
            </div>`;
        });
        $('#card-container').html(html);
    }

    function renderPagination() {
        const maxVisiblePages = 3;
        let html = '';

        const btnStyle = "background-color:white;border:1px solid black;color:black;padding:5px 10px;margin:0 2px;";
        const activeStyle = "background-color:grey;color:white;border:1px solid black;padding:5px 10px;margin:0 2px;";
        const disabledStyle = "background-color:#f0f0f0;border:1px solid #ccc;color:#999;padding:5px 10px;margin:0 2px;cursor:default;";

        html += '<ul style="list-style:none;padding:0;margin:10px 0;text-align:center;">';

        // Previous button
        if (currentPage > 1) {
            html += `<li style="display:inline-block;">
                <button class="page-link" style="${btnStyle}" onclick="changePage(${currentPage - 1})">Prev</button>
            </li>`;
        }

        // First page & ellipsis
        if (currentPage > maxVisiblePages) {
            html += `<li style="display:inline-block;">
                <button class="page-link" style="${btnStyle}" onclick="changePage(1)">1</button>
            </li>`;
            if (currentPage > maxVisiblePages + 1) {
                html += `<li style="display:inline-block;">
                    <span class="page-link" style="${disabledStyle}">...</span>
                </li>`;
            }
        }

        // Middle pages
        const startPage = Math.max(1, currentPage - 1);
        const endPage = Math.min(totalPages, currentPage + 1);
        for (let i = startPage; i <= endPage; i++) {
            html += `<li style="display:inline-block;">
                <button class="page-link" style="${i === currentPage ? activeStyle : btnStyle}" onclick="changePage(${i})">${i}</button>
            </li>`;
        }

        // Last page & ellipsis
        if (currentPage < totalPages - maxVisiblePages + 1) {
            if (currentPage < totalPages - maxVisiblePages) {
                html += `<li style="display:inline-block;">
                    <span class="page-link" style="${disabledStyle}">...</span>
                </li>`;
            }
            html += `<li style="display:inline-block;">
                <button class="page-link" style="${btnStyle}" onclick="changePage(${totalPages})">${totalPages}</button>
            </li>`;
        }

        // Next button
        if (currentPage < totalPages) {
            html += `<li style="display:inline-block;">
                <button class="page-link" style="${btnStyle}" onclick="changePage(${currentPage + 1})">Next</button>
            </li>`;
        }

        html += '</ul>';
        $('#pagination').html(html);
    }

    function changePage(page) {
        currentPage = page;
        fetchData();
    }

    function handleSearch() {
        currentSearch = $('#searchInput').val().trim();
        currentPage = 1;
        fetchData();
    }

    function forceLogout(studentId, studentName) {
        Swal.fire({
            title: 'Konfirmasi',
            text: `Anda yakin ingin memaksa logout ${studentName}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Logout!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const btn = $(`.force-logout[data-id="${studentId}"]`);
                btn.html('<i class="fa fa-spinner fa-spin"></i>');
                btn.prop('disabled', true);

                $.ajax({
                    url: 'force_logout.php',
                    method: 'POST',
                    data: { id_siswa: studentId },
                    dataType: 'json'
                }).done(function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            fetchData();
                        });
                    } else {
                        Swal.fire('Gagal!', response.message, 'error');
                    }
                }).fail(function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || 'Terjadi kesalahan pada server';
                    Swal.fire('Error!', errorMsg, 'error');
                }).always(function() {
                    btn.html('<i class="fa fa-sign-out"></i> Force Logout');
                    btn.prop('disabled', false);
                });
            }
        });
    }

    function fetchData() {
        const params = {
            page: currentPage,
            limit: cardsPerPage,
            search: currentSearch
        };

        $.ajax({
            url: 'get_online.php',
            method: 'GET',
            data: params,
            dataType: 'json',
            success: function(res) {
                allOnlineStudents = res.data;
                renderCards(res.data);
                totalPages = Math.ceil(res.total / cardsPerPage);
                renderPagination();
                
                let now = new Date();
                $('#last-updated').html(`<i class="fa fa-refresh fa-spin text-success me-1"></i> Terakhir diperbarui: ${now.toLocaleTimeString()}`);
            },
            error: function() {
                console.error('Gagal memuat data dari server');
            }
        });
    }

    $(document).ready(function() {
        fetchData();
        setInterval(fetchData, 60000); // Auto-refresh setiap 1 menit

        $('#searchInput').on('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(handleSearch, 500);
        });
    });

    
document.getElementById('btn-reset-credential').addEventListener('click', function() {
    Swal.fire({
        title: 'Yakin ingin reset semua credential login siswa?',
        text: "Semua siswa akan logout secara paksa!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Ya, reset sekarang!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'reset_credential.php';
        }
    });
});
    </script>
    <?php if (isset($_SESSION['success'])): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: '<?= $_SESSION['success'] ?>',
    confirmButtonColor: '#28a745'
});
</script>
<?php unset($_SESSION['success']); endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Gagal',
    text: '<?= $_SESSION['error'] ?>',
    confirmButtonColor: '#dc3545'
});
</script>
<?php unset($_SESSION['error']); endif; ?>
</body>
</html>