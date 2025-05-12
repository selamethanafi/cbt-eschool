<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('siswa'); // Pastikan siswa sudah login
include '../inc/datasiswa.php';
$query = "SELECT * FROM faq";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
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
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Selamat datang, <?php echo $nama_siswa; ?>!</h5>
                                </div>
                                <div class="card-body">
                                   <div class="card shadow-sm mb-4">
                                        <div class="card-header bg-secondary text-white d-flex align-items-center">
                                            <i class="fa-solid fa-question-circle me-2"></i>
                                            <h5 class="mb-0  text-white"><strong>Pertanyaan Umum (FAQ)</strong></h5>
                                        </div>
                                        <div class="card-body">
                                            <!-- Accordion FAQ -->
                                            <div class="accordion" id="faqAccordion">
                                                <?php 
                                                $icons = ['chalkboard-teacher', 'key', 'redo-alt', 'poll', 'laptop']; // Icon untuk setiap pertanyaan
                                                $i = 0;
                                                while($row = mysqli_fetch_assoc($result)): 
                                                ?>
                                                    <div class="accordion-item mb-2">
                                                        <h2 class="accordion-header" id="heading<?= $row['id']; ?>">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $row['id']; ?>" aria-expanded="false" aria-controls="collapse<?= $row['id']; ?>">
                                                                <i class="fa-solid fa-<?= $icons[$i % count($icons)]; ?> me-2 text-secondary"></i>
                                                                <?= htmlspecialchars($row['question']); ?>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse<?= $row['id']; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $row['id']; ?>" data-bs-parent="#faqAccordion">
                                                            <div class="accordion-body bg-secondary text-white">
                                                                <?= nl2br(htmlspecialchars($row['answer'])); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php 
                                                $i++;
                                                endwhile; 
                                                ?>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
<?php include '../inc/js.php'; ?>
<?php include '../inc/check_activity.php'; ?>
</body>
</html>