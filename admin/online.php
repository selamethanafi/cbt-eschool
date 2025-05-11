<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Who's online</title>
    <?php include '../inc/css.php'; ?>
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
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Who's online</h5>
                                    <small id="last-updated" class="text-muted mb-3"></smallv>
                                </div>
                                <div class="card-body">
                                    <div id="card-container" class="row gy-3"></div>
                                        <nav>
                                            <ul class="pagination justify-content-center mt-3" id="pagination"></ul>
                                        </nav>
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

// Pastikan SweetAlert sudah diinclude di header
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
            btn.html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
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
                        fetchData(); // Refresh data
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

// Update renderCards untuk menyertakan nama siswa
function renderCards(data) {
    let html = '';
    data.forEach(function(row) {
        html += `
        <div class="col-6 col-lg-3 col-xl-2 col-sm-4 col-md-3">
            <div class="card text-dark bg-white border border-secondary">
                <div class="card-header bg-light py-2" style="height:50px; display:flex; align-items:center;">
                    <h5 class="mb-1">${row[1]}</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Kelas:</strong> ${row[2]} ${row[3]}</p>
                    <p class="mb-1"><strong>Last Active:</strong> ${row[4]}</p>
                    <p class="mb-1"><strong>Halaman:</strong> ${row[5]}</p>
                    ${row[6]}
                </div>
                <div class="card-footer bg-light py-2 d-flex justify-content-between align-items-center">
                    
                    <button style="border-radius:30px;" class="btn btn-sm btn-outline-danger force-logout" 
                            data-id="${row[8]}" 
                            onclick="forceLogout('${row[8]}', '${row[1].replace(/'/g, "\\'")}')">
                        <i class="fa fa-sign-out"></i> Force Logout
                    </button>
                </div>
            </div>
        </div>`;
    });
    $('#card-container').html(html);
}
function renderPagination() {
    const maxVisiblePages = 3; // jumlah halaman yang terlihat di tengah
    let html = '';

    const btnStyle = "background-color:white;border:1px solid black;color:black;padding:5px 10px;margin:0 2px;";
    const activeStyle = "background-color:grey;color:white;border:1px solid black;padding:5px 10px;margin:0 2px;";
    const disabledStyle = "background-color:#f0f0f0;border:1px solid #ccc;color:#999;padding:5px 10px;margin:0 2px;cursor:default;";

    html += '<ul style="list-style:none;padding:0;margin:10px 0;text-align:center;">';

    // Prev
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

    // Middle page numbers
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

    // Next
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

function fetchData() {
    $.ajax({
        url: 'get_online.php',
        method: 'GET',
        data: {
            page: currentPage,
            limit: cardsPerPage
        },
        dataType: 'json',
        success: function (res) {
            renderCards(res.data);
            totalPages = Math.ceil(res.total / cardsPerPage);
            renderPagination();

            let now = new Date();
            let formatted = now.toLocaleTimeString();
            $('#last-updated').html('<i class="fa fa-refresh fa-spin text-success me-1"></i> Terakhir diperbarui: ' + formatted);
        },
        error: function () {
            console.error('Gagal memuat data dari server');
        }
    });
}

$(document).ready(function () {
    fetchData();
    setInterval(fetchData, 60000); // auto-refresh setiap 1 menit
});
</script>
</body>
</html>