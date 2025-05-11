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

function renderCards(data) {
    let html = '';
    data.forEach(function (row) {
        html += `
        <div class="col-6 col-lg-2 col-sm-4 col-md-3">
                        <div class="card text-dark bg-white border border-secondary">
                        <div class="card-header bg-light py-2" style="min-height:70px;">
                        <h5 class="card-title mb-1">${row[1]}</h5>
                        </div>
                            <div class="card-body">
                                    <p class="mb-1"><strong>Kelas:</strong> ${row[2]} ${row[3]}</p>
                                    <p class="mb-1"><strong>Last Active:</strong> ${row[4]}</p>
                                    <p class="mb-1"><strong></strong> ${row[5]}</p>
                            </div>
                            <div class="card-footer bg-light py-2">
                                ${row[6]}
                            </div>
                        </div>
                    </div>`;
    });
    $('#card-container').html(html);
}

function renderPagination() {
    let html = '';
    for (let i = 1; i <= totalPages; i++) {
        html += `
        <li class="page-item ${i === currentPage ? 'active' : ''}">
            <button class="page-link" style="background-color:white;border:solid 1px black;color:black" onclick="changePage(${i})">${i}</button>
        </li>`;
    }
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