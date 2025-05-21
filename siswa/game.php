<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('siswa');
include '../inc/datasiswa.php';

$game = $_GET['game'] ?? 'math_puzzle';

$stmt = $koneksi->prepare("
    SELECT siswa.nama_siswa, skor_game.skor
    FROM skor_game
    JOIN siswa ON skor_game.id_siswa = siswa.id_siswa
    WHERE skor_game.nama_game = ?
    ORDER BY skor_game.skor DESC
    LIMIT 10
");
$stmt->bind_param("s", $game);
$stmt->execute();
$res = $stmt->get_result();

$game2 = $_GET['game'] ?? 'scramble';

$stmt2 = $koneksi->prepare("
    SELECT siswa.nama_siswa, skor_game.skor
    FROM skor_game
    JOIN siswa ON skor_game.id_siswa = siswa.id_siswa
    WHERE skor_game.nama_game = ?
    ORDER BY skor_game.skor DESC
    LIMIT 10
");
$stmt2->bind_param("s", $game2);
$stmt2->execute();
$res2 = $stmt2->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Leaderboard - <?= htmlspecialchars($game) ?></title>
    <?php include '../inc/css.php'; ?>
    <style>
        .dashboard-icon {
            font-size: 2rem;
            text-align: center;
            color: #6c757d;
        }

        .card-minimal {
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            transition: 0.3s ease;
            height: 100%;
        }

        .card-minimal:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .table-wrapper {
            max-height: 300px;
            overflow-y: auto;
        }
        .game-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 16px;
        }

        .game-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
            background: linear-gradient(to bottom right, #e9f5ff, #fdfdfd);
        }

        .icon-wrapper {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background-color: #e0f0ff;
            display: flex;
            justify-content: center;
            align-items: center;
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
                            <div class="card-header bg-secondary text-white d-flex align-items-center">
                                Mini Games Math Puzzle
                            </div>
                            <div class="card-body">
                                <div class="container pb-5">
                                    <div class="row g-4">

                                        <!-- Card: Game -->
                                        <div class="col-md-4">
                                            <a href="math_puzzle.php" class="text-decoration-none text-dark">
                                                <div class="card card-minimal h-100 border-0 shadow-sm bg-light game-card">
                                                    <div class="card-body text-center d-flex flex-column justify-content-center align-items-center py-5">
                                                        <div class="icon-wrapper mb-3">
                                                            <i class="fas fa-brain fa-3x text-primary"></i>
                                                        </div>
                                                        <h5 class="card-title fw-bold">Math Puzzle</h5>
                                                        <p class="text-muted mb-2">Uji logika dan kecepatan berhitungmu!</p>
                                                        <span class="badge bg-success mt-2">Mainkan Sekarang</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <!-- Card: Leaderboard -->
                                        <div class="col-md-8">
                                            <div class="card card-minimal h-100">
                                                <div class="card-body">
                                                    <div class="dashboard-icon mb-2"><i class="fas fa-trophy text-warning"></i></div>
                                                    <div class="card-title">Leaderboard - <?= ucfirst(str_replace('_', ' ', htmlspecialchars($game))) ?></div>
                                                    <p class="text-muted small mb-3">10 Skor Tertinggi</p>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped table-sm mb-0">
                                                            <thead class="table-secondary">
                                                                <tr>
                                                                    <th class="text-center">#</th>
                                                                    <th>Nama</th>
                                                                    <th>Skor</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php 
                                                            $rank = 1;
                                                            while ($row = $res->fetch_assoc()):
                                                                $icon = '';
                                                                if ($rank == 1) $icon = '<i class="fas fa-medal text-warning"></i>'; // Gold
                                                                elseif ($rank == 2) $icon = '<i class="fas fa-medal text-secondary"></i>'; // Silver
                                                                elseif ($rank == 3) $icon = '<i class="fas fa-medal" style="color: #cd7f32;"></i>'; // Bronze
                                                            ?>
                                                                <tr>
                                                                    <td class="text-center"><?= $icon ?: $rank ?></td>
                                                                    <td><?= htmlspecialchars($row['nama_siswa']) ?></td>
                                                                    <td><?= (int)$row['skor'] ?></td>
                                                                </tr>
                                                            <?php 
                                                                $rank++;
                                                            endwhile; 
                                                            ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div> <!-- row -->
                                </div> <!-- container -->
                            </div> <!-- card-body -->

                           <div class="card-body">
                                <div class="container pb-5">
                                    <div class="row g-4">

                                        <!-- Card: Game -->
                                        <div class="col-md-4">
                                            <a href="scramble_game.php" class="text-decoration-none text-dark">
                                                <div class="card card-minimal h-100 border-0 shadow-sm bg-light game-card">
                                                    <div class="card-body text-center d-flex flex-column justify-content-center align-items-center py-5">
                                                        <div class="icon-wrapper mb-3">
                                                            <i class="fa fa-puzzle-piece fa-3x text-primary"></i>
                                                        </div>
                                                        <h5 class="card-title fw-bold">Scramble Text (beta)</h5>
                                                        <p class="text-muted mb-2">melatih kosakata, logika berpikir, dan fokus visua!</p>
                                                        <span class="badge bg-success mt-2">Mainkan Sekarang</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <!-- Card: Leaderboard -->
                                        <div class="col-md-8">
                                            <div class="card card-minimal h-100">
                                                <div class="card-body">
                                                    <div class="dashboard-icon mb-2"><i class="fas fa-trophy text-warning"></i></div>
                                                    <div class="card-title">Leaderboard - Scramble</div>
                                                    <p class="text-muted small mb-3">10 Skor Tertinggi</p>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped table-sm mb-0">
                                                            <thead class="table-secondary">
                                                                <tr>
                                                                    <th class="text-center">#</th>
                                                                    <th>Nama</th>
                                                                    <th>Skor</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php 
                                                            $rank2 = 1;
                                                            while ($row2 = $res2->fetch_assoc()):
                                                                $icon2 = '';
                                                                if ($rank2 == 1) $icon2 = '<i class="fas fa-medal text-warning"></i>'; // Gold
                                                                elseif ($rank2 == 2) $icon2 = '<i class="fas fa-medal text-secondary"></i>'; // Silver
                                                                elseif ($rank2 == 3) $icon2 = '<i class="fas fa-medal" style="color: #cd7f32;"></i>'; // Bronze
                                                            ?>
                                                                <tr>
                                                                    <td class="text-center"><?= $icon2 ?: $rank2 ?></td>
                                                                    <td><?= htmlspecialchars($row2['nama_siswa']) ?></td>
                                                                    <td><?= (int)$row2['skor'] ?></td>
                                                                </tr>
                                                            <?php 
                                                                $rank2++;
                                                            endwhile; 
                                                            ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div> <!-- row -->
                                </div> <!-- container -->
                            </div> <!-- card-body -->                                     

                        </div> <!-- card -->
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include 'chatbot.php'; ?>
<?php include '../inc/js.php'; ?>
<?php include '../inc/check_activity.php'; ?>
</body>
</html>
