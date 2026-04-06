<!-- File: application/views/participants/index.php -->
<?php
@session_start();

// Timeout otomatis: 15 menit (900 detik)
$timeout = 900;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit;
}

$_SESSION['last_activity'] = time();
if (!isset($_SESSION['login'])) {
    header("Location: ../../public/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Peserta Senam Prolanis</title>
    <link rel="stylesheet" href="senam_prolanis/public/css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<?php include __DIR__ . '/partials/header.php'; ?>
<body>
    <div class="container-fluid">
        <h4 class="text-center my-2">DAFTAR PESERTA SENAM PROLANIS</h4>
        
        <!-- Tombol untuk ke form input dan Riwayat -->
        <div class="row">
             <div class="col-md-6">
                <a href="index.php?action=form" class="btn btn-success">
                     <i class="fa fa-user-plus"></i> Input Peserta Baru
                </a>
                <a href="index.php?action=riwayat" class="btn btn-primary">
                     <i class="fa fa-history"></i> Riwayat
                </a>
             </div>
             <div class="col-md-6 text-end">
                <input type="search" id="searchInput" class="form-control d-inline-block w-50" placeholder="Cari peserta...">
             </div>
        </div>
        <div class="row mb-3 mt-3 align-items-center">
            <div class="col-md-2 text-md-end">
                <label for="filterTanggal" class="fw-bold">Filter Tanggal:</label>
            </div>
            <div class="col-md-3">
                <select id="filterTanggal" class="form-control">
                     <option value="">Semua Tanggal</option>
                     <?php foreach(array_values(array_unique(array_column($data['participants'], 'tanggal_pelaksanaan'))) as $tanggal): ?>
                         <option value="<?= date("Y-m-d", strtotime($tanggal)) ?>"><?= date("d F Y", strtotime($tanggal)) ?></option>
                     <?php endforeach; ?>
                </select>
             </div>
        </div>

        <?php 
        // Pastikan array peserta tidak kosong lalu lakukan pengurutan.
        if (!empty($data['participants'])) {
            usort($data['participants'], function($a, $b) {
                $dateA = strtotime($a['tanggal_pelaksanaan']);
                $dateB = strtotime($b['tanggal_pelaksanaan']);
                if ($dateA === $dateB) {
                    return $a['nomor'] - $b['nomor'];
                }
                return $dateA - $dateB;
            });
        }
        ?>

        <!-- Tabel Data Peserta -->
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
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($data['participants'])): ?>
                    <?php 
                    $currentDate = '';
                    $counter = 0;
                    foreach($data['participants'] as $p): 
                       if ($currentDate !== $p['tanggal_pelaksanaan']) {
                           $currentDate = $p['tanggal_pelaksanaan'];
                           $counter = 1;
                       } else {
                           $counter++;
                       }
                ?>
                        <tr>
                            <td><?= $counter ?></td>
                            <td data-date="<?= date("Y-m-d", strtotime($p['tanggal_pelaksanaan'])) ?>">
                                <?= date("d F Y", strtotime($p['tanggal_pelaksanaan'])) ?>
                            </td>
                            <td><?= $p['nama_pasien'] ?></td>
                            <td><?= $p['nomor_kartu_bpjs'] ?></td>
                            <td><?= $p['tinggi_badan'] ?></td>
                            <td><?= $p['berat_badan'] ?></td>
                            <td><?= $p['lingkar_perut'] ?></td>
                            <td><?= $p['sistole'] ?></td>
                            <td><?= $p['diastole'] ?></td>
                            <td><?= $p['respiratory_rate'] ?></td>
                            <td><?= $p['heart_rate'] ?></td>
                            <td>
                                <a href="index.php?action=delete&id=<?= $p['id'] ?>" class="btn btn-danger btn-sm"
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                   <i class="fa fa-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="12" class="text-center">Belum ada data</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div id="pagination" class="mt-3 d-flex justify-content-center"></div>
</body>

<script>
document.getElementById("searchInput").addEventListener("keyup", function() {
    let input = this.value.toLowerCase();
    let rows = document.querySelectorAll("tbody tr");

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
});
</script>

<script>
document.getElementById("filterTanggal").addEventListener("change", function() {
    let selectedDate = this.value;
    let rows = document.querySelectorAll("tbody tr");

    rows.forEach(row => {
        let rowDate = row.querySelector("[data-date]").getAttribute("data-date"); 
        if (!selectedDate || rowDate === selectedDate) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});
</script>

<!-- Script Pagination -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  const rows = document.querySelectorAll("tbody tr");
  const rowsPerPage = 4;
  let currentPage = 1;
  const pagination = document.getElementById("pagination");

  function displayRows() {
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    rows.forEach((row, index) => {
      row.style.display = (index >= start && index < end) ? "" : "none";
    });
  }

  function setupPagination() {
    pagination.innerHTML = "";
    const pageCount = Math.ceil(rows.length / rowsPerPage);

    // Prev button
    const prevBtn = document.createElement("button");
    prevBtn.className = "btn btn-secondary mx-1";
    prevBtn.textContent = "Prev";
    prevBtn.disabled = currentPage === 1;
    prevBtn.onclick = () => {
      if (currentPage > 1) {
        currentPage--;
        displayRows();
        setupPagination();
      }
    };
    pagination.appendChild(prevBtn);

    // Page numbers
    for (let i = 1; i <= pageCount; i++) {
      const pageBtn = document.createElement("button");
      pageBtn.className = "btn " + (i === currentPage ? "btn-primary" : "btn-light") + " mx-1";
      pageBtn.textContent = i;
      pageBtn.onclick = () => {
        currentPage = i;
        displayRows();
        setupPagination();
      };
      pagination.appendChild(pageBtn);
    }

    // Next button
    const nextBtn = document.createElement("button");
    nextBtn.className = "btn btn-secondary mx-1";
    nextBtn.textContent = "Next";
    nextBtn.disabled = currentPage === pageCount;
    nextBtn.onclick = () => {
      if (currentPage < pageCount) {
        currentPage++;
        displayRows();
        setupPagination();
      }
    };
    pagination.appendChild(nextBtn);
  }

  displayRows();
  setupPagination();
});
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>
</html>