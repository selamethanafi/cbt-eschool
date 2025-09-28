<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Hapus Sesi Login</title>
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
                  <h5 class="card-title mb-0">Daftar Siswa Login</h5>
                </div>
                <div class="card-body">
                  <div class=" table-wrapper">
                  <table id="siswaTable" class="table table-striped nowrap">
                  <thead>
                      <tr>
                        <th style="display:none;">ID</th> <!-- kolom tersembunyi -->
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Sesi</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $no = 1;
                      $query = mysqli_query($koneksi, "SELECT * FROM siswa where `session_token` is NOT NULL ORDER BY id_siswa DESC");
                      while ($data = mysqli_fetch_assoc($query)) {
                        echo "<tr>";
                        echo "<td style='display:none;'>{$data['id_siswa']}</td>"; // kolom untuk sorting
                        echo "<td>{$no}</td>";
                        echo "<td>{$data['nama_siswa']}</td>";
                        echo "<td>{$data['kelas']}</td><td>{$data['session_token']}</td>";
                        echo '<td>
                                <form method="POST" action="hapus_sesi_token.php" class="d-inline delete-form" style="display:inline;">
                                  <input type="hidden" name="id" value="' . $data['id_siswa'] . '">
                                  <button type="submit" class="btn btn-danger btn-sm btn-delete">
                                    <i class="fa fa-close"></i> Hapus
                                  </button>
                                </form>
                              </td>';
                        echo "</tr>";
                        $no++;
                      }
                      ?>
                    </tbody>
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
  <!-- Modal -->
<div class="modal fade" id="mdunduh" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi Unduh dari SIM</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Yakin hendak mengunduh siswa dari sistem informasi madrasah?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batak</button>
        <a href="sinkron_peserta.php" class="btn btn-success">Yakin</a>
      </div>
    </div>
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
          title: 'Yakin ingin membolehkan murid login lagi?',
          text: "Pastikan memang murid hanya mengakses dari satu perangkat!",
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
