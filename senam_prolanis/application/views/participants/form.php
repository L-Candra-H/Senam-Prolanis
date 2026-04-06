<!-- File: application/views/participants/form.php -->
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
  <title>Isian Data Peserta Senam Prolanis</title>
  <link rel="stylesheet" href="senam_prolanis/public/css/style.css">
  <!-- Sertakan Bootstrap (minimal) -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Sertakan Google Material Icons (untuk sentuhan material design) -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<?php include __DIR__ . '/partials/header.php'; ?>
<body>
  <div class="container">
    <h2 class="text-center my-2">FORM ISIAN DATA PESERTA</h2>
    <?php if (isset($data['errors'])): ?>
      <div class="alert alert-danger">
        <ul>
          <?php foreach ($data['errors'] as $error): ?>
            <li><?= $error ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php
      $isEdit = isset($participant);
      $formAction = $isEdit ? 'update' : 'store';
    ?>
    <form action="index.php?action=<?= $formAction ?>" method="POST" class="form-grid">
      <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= $participant['id'] ?>">
      <?php endif; ?>

      <!-- Header Bulan-Tahun (sejajar dengan CSS Flex) -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <label>Bulan:</label>
          <select name="bulan" class="form-control">
            <option value="">-- Pilih Bulan --</option>
            <?php
            $daftarBulan = [
              1=>'JANUARI', 2=>'FEBRUARI', 3=>'MARET', 4=>'APRIL', 5=>'MEI', 6=>'JUNI',
              7=>'JULI', 8=>'AGUSTUS', 9=>'SEPTEMBER', 10=>'OKTOBER', 11=>'NOVEMBER', 12=>'DESEMBER'
            ];
            foreach ($daftarBulan as $num => $bulan) {
              $selected = ($isEdit && $participant['bulan'] == $num) ? 'selected' : '';
              echo "<option value='$num' $selected>$bulan</option>";
            }
            ?>
          </select>
        </div>

        <div>
          <label>Tahun:</label>
          <select name="tahun" class="form-control">
            <option value="">-- Pilih Tahun --</option>
            <?php
            $currentYear = date("Y");
            for ($i = 2024; $i <= $currentYear + 3; $i++) {
              $selected = ($isEdit && $participant['tahun'] == $i) ? 'selected' : '';
              echo "<option value='$i' $selected>$i</option>";
            }
            ?>
          </select>
        </div>

      </div>

      <!-- Tanggal Pelaksanaan -->
      <div class="form-group">
        <label>Tanggal Pelaksanaan</label>
        <input type="date" name="tanggal_pelaksanaan" class="form-control" required
               value="<?= $isEdit ? $participant['tanggal_pelaksanaan'] : '' ?>">
      </div>

      <!-- Isian Data Pasien (Nomor otomatis, Nama Pasien dan Nomor Kartu BPJS) -->
      <div class="form-group">
        <label>Nomor (Otomatis):</label>
        <input type="text" name="nomor" class="form-control" readonly
               value="<?= $isEdit ? $participant['nomor'] : 'Akan ditentukan setelah disimpan' ?>">
      </div>

      <!-- Nama Pasien dan Nomor Kartu BPJS sejajar -->
      <div class="container">
          <h2 class="text-center my-2">IDENTITAS DAN DATA KESEHATAN</h2>
          <div class="row">
             <div class="col-md-6">
                 <label for="nama_pasien" class="fw-bold">Nama Pasien</label>
                 <input type="text" id="nama_pasien" name="nama_pasien" class="form-control" placeholder="Masukkan nama lengkap" required
                        value="<?= $isEdit ? $participant['nama_pasien'] : '' ?>">
             </div>
             <div class="col-md-6">
                 <label for="nomor_kartu_bpjs" class="fw-bold">Nomor Kartu BPJS</label>
                 <input type="text" id="nomor_kartu_bpjs" name="nomor_kartu_bpjs" class="form-control" placeholder="Masukkan nomor BPJS"
                        minlength="13" maxlength="13" pattern="\d{13}" required
                        title="Nomor BPJS harus terdiri dari 13 digit angka"
                        value="<?= $isEdit ? $participant['nomor_kartu_bpjs'] : '' ?>"> 
             </div>
          </div>
      </div>

      <!-- Pemeriksaan Fisik dan Tekanan Darah -->
      <div class="row">
        <div class="col-md-6">
          <h5>PEMERIKSAAN FISIK</h5>
          <div class="form-group d-flex align-items-center">
            <label class="mr-3 col-form-label">Tinggi Badan</label>
            <input type="number" name="tinggi_badan" class="form-control flex-grow-1" required
                   value="<?= $isEdit ? $participant['tinggi_badan'] : '' ?>">
          </div>
          <div class="form-group d-flex align-items-center">
            <label class="mr-3 col-form-label">Berat Badan</label>
            <input type="number" name="berat_badan" class="form-control flex-grow-1" required
                   value="<?= $isEdit ? $participant['berat_badan'] : '' ?>">
          </div>
          <div class="form-group d-flex align-items-center">
            <label class="mr-3 col-form-label">Lingkar Perut</label>
            <input type="number" name="lingkar_perut" class="form-control flex-grow-1" required
                   value="<?= $isEdit ? $participant['lingkar_perut'] : '' ?>">
          </div>
        </div>
        <div class="col-md-6">
          <h5>TEKANAN DARAH</h5>
          <div class="form-group d-flex align-items-center">
            <label class="mr-3 col-form-label">Sistole</label>
            <input type="number" name="sistole" class="form-control flex-grow-1" required
                   value="<?= $isEdit ? $participant['sistole'] : '' ?>">
          </div>
          <div class="form-group d-flex align-items-center">
            <label class="mr-3 col-form-label">Diastole</label>
            <input type="number" name="diastole" class="form-control flex-grow-1" required
                   value="<?= $isEdit ? $participant['diastole'] : '' ?>">
          </div>
          <div class="form-group d-flex align-items-center">
            <label class="mr-3 col-form-label">Respiratory Rate</label>
            <input type="number" name="respiratory_rate" class="form-control flex-grow-1" required
                   value="<?= $isEdit ? $participant['respiratory_rate'] : '' ?>">
          </div>
          <div class="form-group d-flex align-items-center">
            <label class="mr-3 col-form-label">Heart Rate</label>
            <input type="number" name="heart_rate" class="form-control flex-grow-1" required
                   value="<?= $isEdit ? $participant['heart_rate'] : '' ?>">
          </div>
        </div>
      </div>

      <!-- Tombol Simpan & Kembali -->
      <div class="mt-3 d-flex justify-content-between">
        <button type="submit" class="btn btn-primary">
             <i class="fa fa-save"></i> Simpan
        </button>
        <a href="index.php?action=index" class="btn btn-secondary">
             <i class="fa fa-arrow-left"></i> Kembali
        </a>
      </div>

    </form>
  </div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    <?php if (isset($data['errors']) && !empty($data['errors'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            html: '<?= implode("<br>", $data['errors']) ?>',
            confirmButtonColor: '#d33',
            confirmButtonText: 'OK'
        });
    <?php endif; ?>
});
</script>
<script>
document.getElementById("nomor_kartu_bpjs").addEventListener("input", function () {
    let val = this.value;
    if (!/^\d{0,13}$/.test(val)) {
        this.setCustomValidity("Nomor BPJS hanya boleh berisi angka, maksimal 13 digit.");
    } else {
        this.setCustomValidity("");
    }
});
</script>
</body>

<?php include __DIR__ . '/partials/footer.php'; ?>
</html>

