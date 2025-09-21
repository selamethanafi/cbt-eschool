<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
	function via_curl($url_ard_unduh)
	{
		$file = $url_ard_unduh;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $file);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$xmldata = curl_exec($ch);
		curl_close($ch);
		$json = json_decode($xmldata, true);
		return $json;	
	}

if(isset($_GET['id']))
{
    $id = $_GET['id'];
}
else
{
    $id = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manajemen Siswa</title>
  <?php include '../inc/css.php'; ?>
  <style>
    .table-wrapper {
      overflow-x: auto !important;
      -webkit-overflow-scrolling: touch;
    }
    table th, table td {
    text-align: left !important;
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
                <div class="card-header">
                  <h5 class="card-title mb-0">Menunduh Daftar Siswa dari Sistem Informasi Madrasah</h5>
                  <h2>Mohon menunggu</h2>
                </div>
                
                <?php
           		if(empty($id))
        		{
        			$id = 0;
        		}
        		$ta = mysqli_query($koneksi, "SELECT * FROM `cbt_konfigurasi` WHERE `konfigurasi_kode` = 'app_key_server_cbt_lokal'");
        		$key = '';
                while($da = mysqli_fetch_assoc($ta))
                {
                    $key = $da['konfigurasi_isi'];
                }
                //echo $key;
                $ta = mysqli_query($koneksi, "SELECT * FROM `cbt_konfigurasi` WHERE `konfigurasi_kode` = 'cbt_sianis'");
        		$sianis = '';
                while($da = mysqli_fetch_assoc($ta))
        		{
        			$sianis = $da['konfigurasi_isi'];
        		}
        		if((!empty($key)) and (!empty($sianis)))
        		{
                    $url = $sianis.'/cbtzya/jml_peserta/'.$key.'/semua';
        			$json = via_curl($url);
        			$cacah = 0;
        			if($json)
        			{
		            	foreach($json as $dm)
        				{
        					$cacah = $dm['cacah'];
        				}
        			}
                    ?>
                   <div class="card-body">
                       <?php
                   if($cacah > 0)
        		    {
		    	    	if($id <= $cacah )
        				{
        					$url = $sianis.'/cbtzya/peserta/'.$key.'/'.$id;
        //					die($url);
        					$json = via_curl($url);
        					if($json)
        					{
        						if($id == 0)
        						{
        						}
        			        	foreach($json as $dm)
        						{
        							$pesan = $dm['pesan'];
		        					if($pesan == 'ada')
        							{
		        						$nis = mysqli_real_escape_string($koneksi,$dm['nisn']);
        								$username = mysqli_real_escape_string($koneksi,$dm['username']);
        								$password = mysqli_real_escape_string($koneksi,$dm['password']);
        								$nama = mysqli_real_escape_string($koneksi,$dm['nama']);
        								$kelas = mysqli_real_escape_string($koneksi,$dm['nama_kelas']);
        								$rombel = mysqli_real_escape_string($koneksi,$dm['ruang']);
        								$agen = mysqli_real_escape_string($koneksi,$dm['agen']);
        								$versi = mysqli_real_escape_string($koneksi,$dm['versi']);
                                        // Enkripsi password
                                        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
                                        $encrypted = openssl_encrypt($password, $method, $rahasia, 0, $iv);
                                        $final = base64_encode($iv . $encrypted);
        								if(empty($password))
        								{
        									die($nama.' password masih kosong, buat dulu');
		        						}
		        						if ($nama && $username && $password && $kelas && $rombel)
		        						{
                                        // Cek duplikat
                                        $cek = mysqli_query($koneksi, "SELECT * FROM siswa WHERE `nis` = '$nis'");
                                        if (mysqli_num_rows($cek) > 0) 
                                        {
                                            $sql = "update `siswa` set `nama_siswa` = '$nama', `username` = '$username', `password` = '$final', `kelas` = '$kelas', `rombel` = '$rombel' where `nis` = '$nis'";
                                            $insert = mysqli_query($koneksi, $sql);                                            
                                        }
                                        else 
                                        {

                                            // Insert DB
                                            $sql = "INSERT INTO siswa (nama_siswa, username, password, kelas, rombel, `nis`) VALUES ('$nama', '$username', '$final', '$kelas', '$rombel', '$nis')";
                                            $insert = mysqli_query($koneksi, $sql);
                                        }
		        						}
    						        } // kalau pesan = ada
        						} // foreach json
        					} // kalau json tidak error
        					$id++;
        					echo 'Terproses '.$id.' dari '.$cacah.' siswa';
    					    $lanjut = 'sinkron_peserta.php?id='.$id;
        //					die($lanjut);
                            ?>
	        				<script>setTimeout(function () {
			    			   window.location.href= '<?php echo $lanjut;?>';
				            	},1);
        					</script>
        					<?php
                                           echo $cacah;
        				}
        				        		    else
        		    {
                            ?>
	        				<script>setTimeout(function () {
			    			   window.location.href= 'siswa.php';
				            	},1);
        					</script>
        					<?php

        		    }

                                           ?>
                    </div>
                <?php
        		    }
        		}        
                
                
                
                
                
                
                
                ?>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
<?php include '../inc/js.php'; ?>
  <!--<script src="../assets/datatables/dataTables.buttons.min.js"></script>
  <script src="../assets/datatables/buttons.bootstrap5.min.js"></script>-->
  <script src="../assets/datatables/jszip.min.js"></script>
  <script src="../assets/datatables/buttons.html5.min.js"></script>
  <script>
    const table = $('#siswaTable').DataTable({
  dom: 
        // Baris 1: Export buttons + Search box
        '<"row mb-3"' +
            '<"col-md-6 d-flex align-items-center">' +
            '<"col-md-6 d-flex justify-content-end"f>' +
        '>' +
        // Baris 2: Length dropdown + pagination
        '<"row mb-3"' +
            '<"col-md-6 d-flex align-items-center"l>' +
            '<"col-md-6 d-flex justify-content-end"p>' +
        '>' +
        // Table
        't' +
        // Baris 3: Info + pagination bawah
        '<"row mt-3"' +
            '<"col-md-6 d-flex align-items-center"i>' +
            '<"col-md-6 d-flex justify-content-end"p>' +
        '>',
  paging: true,
  lengthChange: true,
  searching: true,
  ordering: true,
  info: true,
  autoWidth: true,
  responsive: true,
  order: [[0, 'desc']], // Urutkan berdasarkan kolom tersembunyi ID
  columnDefs: [
    { targets: 0, visible: false }, // Sembunyikan kolom ID
  ],
  buttons: [
    {
      extend: 'excelHtml5',
      title: 'Daftar Siswa',
      exportOptions: {
        columns: [1, 2, 3, 4, 5] 
      }
    }
  ]
});

    // Trigger export dari tombol luar
    $('#exportExcel').on('click', function () {
      table.button('.buttons-excel').trigger();
    });

    // Konfirmasi Hapus
    document.querySelectorAll('.delete-form').forEach(form => {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({
          title: 'Yakin ingin menghapus?',
          text: "Data siswa yang dihapus tidak bisa dikembalikan!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, hapus!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });
  </script>
  <?php if (isset($_SESSION['success'])): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '<?= $_SESSION['success']; ?>',
    confirmButtonColor: '#28a745'
});
</script>
<?php unset($_SESSION['success']); endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: '<?= $_SESSION['error']; ?>',
    confirmButtonColor: '#dc3545'
});
</script>
<?php unset($_SESSION['error']); endif; ?>
<?php if (isset($_SESSION['alert'])): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: '<?= $_SESSION['error']; ?>',
    confirmButtonColor: '#dc3545'
});
</script>
<?php unset($_SESSION['error']); endif; ?>
</body>
</html>
