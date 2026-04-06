<!-- File: application/views/participants/cetak.php -->
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
    <title>Cetak Data Peserta Senam Prolanis</title>
    <link rel="stylesheet" href="senam_prolanis/public/css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Pengaturan tampilan saat cetak */
        @media print {
            body {
                width: 100%;
                margin: 0;
                padding: 0;
            }
            .container {
                width: 100%;
            }
            table {
                width: 100%;
                font-size: 12px;
            }
            th, td {
                padding: 5px;
                text-align: center;
            }
            h1 {
                font-size: 36px;
                font-weight: bold;
                text-align: center;
            }
            .info {
                font-size: 18px;
                text-align: center;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<?php include __DIR__ . '/partials/header.php'; ?>
<body>
    <div class="container">
        <!-- Judul -->
        <h1 class="text-center">DATA PESERTA</h1>

        <!-- Tabel Cetak Data Peserta -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>NO</th>
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
                <?php if (!empty($data['participants'])): ?>
                    <?php 
                    $currentDate = '';
                    $counter = 0;
                    foreach ($data['participants'] as $p): 
                        if ($currentDate !== $p['tanggal_pelaksanaan']) {
                            $currentDate = $p['tanggal_pelaksanaan'];
                            $counter = 1;
                        } else {
                            $counter++;
                        }
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
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11" class="text-center">Tidak ada data untuk periode ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
<?php include __DIR__ . '/partials/footer.php'; ?>
</html>

