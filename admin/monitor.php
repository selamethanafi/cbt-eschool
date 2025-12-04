<?php
$lifetime = 24 * 60 * 60;
session_set_cookie_params($lifetime);
ini_set('session.gc_maxlifetime', $lifetime);
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
include '../inc/dataadmin.php';
// Cek jika sudah login
check_login('admin');
if(isset($_GET['aksi']))
{
	$aksi = $_GET['aksi'];
	}
else
{
	$aksi = '';	
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Ujian Siswa</title>
<?php include '../inc/css.php'; ?>
</head>

<body>
    <div class="wrapper">

    <?php include 'sidebar.php'; ?>

<div class="main">
    <?php include 'navbar.php'; ?>
            <!-- /Navbar -->

            <!-- Content -->
            <main class="content">
                <div class="container-fluid p-0">
                    <div class="row">
                        <div class="col-12">
                        <?php
                                $ta = mysqli_query($koneksi, "SELECT * FROM `reset`");
                                if(mysqli_num_rows($ta) > 0)
				{
					?>
					<div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Daftar Permintaan Reset</h5>
                                </div>
                            </div>
                                    <div class="card-body">
	                                    <div class="table-wrapper">
                             		           <table class="table table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Nama Siswa</th>
                                                    <th>Rest</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <?php
                                            while($da=mysqli_fetch_assoc($ta))
                                            {
                                            	echo '<tr><td>'.$da['nama'].'</td><td>'.$da['macam'].'</td><td>';
                                            	if($da['macam'] == 'perangkat')
                                            	{
                                            		echo '<a href="reset_siswa.php?reset=perangkat&id_siswa='.$da['id_siswa'].'" class="btn btn-danger">Reset</a>';
                                            	}
                                            	if($da['macam'] == 'tes')
                                            	{
                                            		echo '<a href="reset_siswa.php?reset=tes&id_siswa='.$da['id_siswa'].'&kode_soal='.$da['kode_soal'].'" class="btn btn-warning">Reset</a>';
                                            	}
                                            	echo '</td></tr>';
                                            }
                                            ?>
                                        </table>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                                <div class="card-body">
                                <div class=" table-wrapper">
                                <h1 class="text-danger">Daftar peserta belum rampung</h1>
                  <table id="siswaTable" class="table table-striped nowrap">
                  <thead>
                      <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Kode Soal</th>
                        <th>Nama Tes</th>
                      </tr>
                    </thead>
                    <tbody>
		<?php
		$te = mysqli_query($koneksi, "SELECT * FROM `siswa_belum_rampung` order by `nama_siswa`");
		$no = 1;
		while ($data = mysqli_fetch_assoc($te)) {
		echo "<tr>";
                        echo "<td>{$no}</td>";
                        echo "<td>{$data['nama_siswa']}</td>";
                        echo "<td>{$data['kode_soal']}</td>";
                        echo "<td>{$data['nama_soal']}</td>";
			echo "</tr>";
			$no++;
		}
		?>
		</tbody>
                  </table>
                    </div>      
                                </div>
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Monitor Ujian</h5>
                                    <small id="last-updated" class="text-muted"></small>
                                </div>
                            </div>
				
                                <div class="card-body">
                                    <div class="table-wrapper">
                                        <table id="monitor" class="table table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Nama Siswa</th>
                                                    <th>Kode Soal</th>
                                                    <th>Waktu Sisa</th>
                                                    <th>Waktu Mulai</th>
                                                    <th>Status Ujian</th>
                                                    <th>Progres Ujian</th>
                                                    <?php
                                                    if($aksi == 'paksa')
							{
                                                    echo '<th>Aksi</th>';
                                                    }?>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>
    		<script>setTimeout(function () {
		 window.location.href= 'monitor.php';
			},<?php echo 300000;?>);
			</script>
<?php include '../inc/js.php'; ?>
<?php
if($aksi == 'paksa')
{
?>

<script>
$(document).ready(function () {
    var table = $('#monitor').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 50,
        ajax: {
            url: 'monitor_data.php',
            type: 'GET'
        }, 
        columns: [
            { data: 'nama_siswa', title: 'Nama Siswa' },
            { data: 'kode_soal', title: 'Kode Soal' },
            { data: 'progres', title: 'Progres Ujian' },
            { data: 'waktu_sisa', title: 'Waktu Sisa' },
            { data: 'waktu_dijawab', title: 'Waktu Dijawab' },
            { data: 'status_badge', title: 'Status Ujian' },
            { data: 'aksi', title: 'Aksi' }
        ],
        columnDefs: [
            { targets: 4, orderable: false, searchable: false }
        ],
        initComplete: function () {
            // Panggil pertama kali
            updateTimestamp();

            // Ulangi setiap 1 menit
            setInterval(function () {
                table.ajax.reload(null, false); // Reload data tanpa reset halaman
                updateTimestamp();
            }, 60000); // 60 detik
        }
    });

    function updateTimestamp() {
        let now = new Date();
        let formatted = now.toLocaleTimeString();
        $('#last-updated').html(
            '<i class="fa fa-refresh fa-spin me-1" style="color:green;" aria-hidden="true"></i>' +
            'Terakhir diperbarui: ' + formatted
        );
    }
});
</script>
<?php
}
else
{
?>

<script>
$(document).ready(function () {
    var table = $('#monitor').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 50,
        ajax: {
            url: 'monitor_data.php',
            type: 'GET'
        }, 
        columns: [
            { data: 'nama_siswa', title: 'Nama Siswa' },
            { data: 'kode_soal', title: 'Kode Soal' },
            { data: 'progres', title: 'Progres Ujian' },
            { data: 'waktu_sisa', title: 'Waktu Sisa' },
            { data: 'waktu_dijawab', title: 'Waktu Dijawab' },
            { data: 'status_badge', title: 'Status Ujian' }
        ],
        columnDefs: [
            { targets: 4, orderable: false, searchable: false }
        ],
        initComplete: function () {
            // Panggil pertama kali
            updateTimestamp();

            // Ulangi setiap 1 menit
            setInterval(function () {
                table.ajax.reload(null, false); // Reload data tanpa reset halaman
                updateTimestamp();
            }, 60000); // 60 detik
        }
    });

    function updateTimestamp() {
        let now = new Date();
        let formatted = now.toLocaleTimeString();
        $('#last-updated').html(
            '<i class="fa fa-refresh fa-spin me-1" style="color:green;" aria-hidden="true"></i>' +
            'Terakhir diperbarui: ' + formatted
        );
    }
});
</script>
<?php
}
?>
<script>
$(document).on('click', '.simpan-paksa-btn', function () {
    const kodeSoal = $(this).data('kode');
    const idSiswa = $(this).data('siswa');
    const namaSiswa = $(this).data('nama');

    Swal.fire({
        title: 'Yakin simpan paksa ujian ini?',
        html: `<b>${namaSiswa}</b> akan dianggap <b>selesai</b> mengerjakan Ujian (<code>${kodeSoal}</code>)`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Simpan Paksa!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `simpan_paksa.php?kode_soal=${kodeSoal}&id_siswa=${idSiswa}`;
        }
    });
});
</script>
<?php if (isset($_SESSION['success_message'])): ?>
<script>
    $(document).ready(function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= $_SESSION['success_message']; ?>',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    });
</script>
<?php unset($_SESSION['success_message']); endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
<script>
    $(document).ready(function() {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '<?= $_SESSION['error_message']; ?>',
            confirmButtonColor: '#d33',
            confirmButtonText: 'OK'
        });
    });
</script>
<?php unset($_SESSION['error_message']); endif; ?>

</body>
</html>
