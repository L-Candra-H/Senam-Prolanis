<!-- Bagian HEAD -->
<head>
  <meta charset="UTF-8">
  <title>Riwayat Peserta Senam Prolanis</title>
  <link rel="stylesheet" href="senam_prolanis/public/css/style.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    @media print {
      #logoutBtn, .hide-during-cetak {
        display: none !important;
      }
    }
  </style>
</head>

<body>
  <?php include __DIR__ . '/partials/header.php'; ?>
  <div class="container-fluid">
    <h2 class="text-center my-2">RIWAYAT PESERTA</h2>

    <div class="d-flex flex-wrap align-items-baseline gap-3 mb-3">
      <!-- Form Filter -->
      <form method="GET" action="index.php" class="form-inline">
        <input type="hidden" name="action" value="riwayat">
        <div class="form-group mb-2 mr-2">
          <label for="filter_bulan" class="mr-2">Bulan:</label>
          <select name="filter_bulan" id="filter_bulan" class="form-control">
            <option value="">-- Pilih Bulan --</option>
            <?php
            $daftarBulan = [
              1=>'JANUARI', 2=>'FEBRUARI', 3=>'MARET', 4=>'APRIL', 5=>'MEI', 6=>'JUNI',
              7=>'JULI', 8=>'AGUSTUS', 9=>'SEPTEMBER', 10=>'OKTOBER', 11=>'NOVEMBER', 12=>'DESEMBER'
            ];
            $selectedBulan = $_GET['filter_bulan'] ?? '';
            foreach ($daftarBulan as $num => $nama) {
              $selected = ($selectedBulan == $num) ? 'selected' : '';
              echo "<option value='$num' $selected>$nama</option>";
            }
            ?>
          </select>
        </div>

        <div class="form-group mb-2 mr-2">
          <label for="filter_tahun" class="mr-2">Tahun:</label>
          <select name="filter_tahun" id="filter_tahun" class="form-control">
            <option value="">-- Pilih Tahun --</option>
            <?php
            $currentYear = date("Y");
            $selectedTahun = $_GET['filter_tahun'] ?? '';
            for ($i = 2024; $i <= $currentYear + 3; $i++) {
              $selected = ($selectedTahun == $i) ? 'selected' : '';
              echo "<option value='$i' $selected>$i</option>";
            }
            ?>
          </select>
        </div>

        <button type="submit" class="btn btn-primary mb-2">
          <i class="fa fa-search"></i> Cari
        </button>
      </form>

      <?php if (!empty($_GET['filter_bulan']) && !empty($_GET['filter_tahun']) && !empty($data['participants'])): ?>
        <button type="button" class="btn btn-success mb-2 me-3" id="btnCetak">
          <i class="fa fa-print"></i> Cetak Data
        </button>
      <?php endif; ?>

      <a href="index.php?action=index" class="btn btn-secondary mb-2">
        <i class="fa fa-arrow-left"></i> Kembali
      </a>
    </div>

    <?php
    if (!isset($_GET['filter_bulan']) || !isset($_GET['filter_tahun']) ||
        empty($_GET['filter_bulan']) || empty($_GET['filter_tahun'])) {
      $data['participants'] = [];
    }
    ?>

    <!-- Pencarian Data -->
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Cari peserta...">

    <!-- Tabel Data -->
    <div class="table-responsive mt-3">
      <table class="table table-hover table-bordered">
        <thead>
          <tr>
            <th>NOMOR</th>
            <th>TANGGAL PELAKSANAAN</th>
            <th>NAMA PASIEN</th>
            <th>NOMOR KARTU BPJS</th>
            <th>TINGGI BADAN</th>
            <th>BERAT BADAN</th>
            <th>LINGKAR PERUT</th>
            <th>SISTOLE</th>
            <th>DIASTOLE</th>
            <th>RESPIRATORY RATE</th>
            <th>HEART RATE</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($data['participants'])):
            $currentDate = '';
            $counter = 0;
            foreach ($data['participants'] as $p):
              $counter = ($currentDate !== $p['tanggal_pelaksanaan']) ? 1 : $counter + 1;
              $currentDate = $p['tanggal_pelaksanaan'];
          ?>
              <tr>
                <td><?= $counter ?></td>
                <td><?= date("d F Y", strtotime($p['tanggal_pelaksanaan'])) ?></td>
                <td><?= $p['nama_pasien'] ?></td>
                <td><?= $p['nomor_kartu_bpjs'] ?></td>
                <td><?= $p['tinggi_badan'] ?></td>
                <td><?= $p['berat_badan'] ?></td>
                <td><?= $p['lingkar_perut'] ?></td>
                <td><?= $p['sistole'] ?></td>
                <td><?= $p['diastole'] ?></td>
                <td><?= $p['respiratory_rate'] ?></td>
                <td><?= $p['heart_rate'] ?></td>
              </tr>
          <?php endforeach; else: ?>
            <tr><td colspan="12" class="text-center">Tidak ada data untuk periode ini.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Modal Cetak -->
    <?php if (!empty($_GET['filter_bulan']) && !empty($_GET['filter_tahun'])): ?>
    <div class="modal fade" id="cetakModal" tabindex="-1" aria-labelledby="cetakModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header justify-content-between align-items-center">
            <h5 class="modal-title" id="cetakModalLabel">Cetak Data Peserta</h5>
            <div>
              <button class="btn btn-primary mr-2" onclick="printIframe()">
                <i class="fa fa-print"></i> Cetak
              </button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">
                <i class="fa fa-times"></i> Tutup
              </button>
            </div>
          </div>
          <div class="modal-body p-0">
            <iframe src="index.php?action=cetak&filter_bulan=<?= $_GET['filter_bulan'] ?>&filter_tahun=<?= $_GET['filter_tahun'] ?>" width="100%" height="500px" style="border: none;"></iframe>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <!-- Script -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    function printIframe() {
      const iframe = document.querySelector('#cetakModal iframe');
      iframe.contentWindow.focus();
      iframe.contentWindow.print();
    }

    const cetakButton = document.getElementById("btnCetak");
    if (cetakButton) {
      cetakButton.addEventListener("click", function () {
        const bulan = document.getElementById("filter_bulan")?.value;
        const tahun = document.getElementById("filter_tahun")?.value;

        if (bulan && tahun) {
          $('#cetakModal').modal('show');
        } else {
          Swal.fire({
            icon: 'warning',
            title: 'Filter Belum Lengkap',
            text: 'Silakan pilih Bulan dan Tahun terlebih dahulu untuk mencetak data.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
          });
        }
      });
    }

    const searchField = document.getElementById("searchInput");
    if (searchField) {
      searchField.addEventListener("keyup", function () {
        let input = this.value.toLowerCase();
        let rows = document.querySelectorAll("tbody tr");

        rows.forEach(row => {
          let text = row.textContent.toLowerCase();
          row.style.display = text.includes(input) ? "" : "none";
        });
      });
    }

    $('#cetakModal').on('shown.bs.modal', function () {
      $('#logoutBtn').hide();
    });

    $('#cetakModal').on('hidden.bs.modal', function () {
      $('#logoutBtn').show();
    });
  </script>

  <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
