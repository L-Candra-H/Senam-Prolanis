<?php
// File: application/controllers/ParticipantController.php
require_once __DIR__ . '/../models/Participant.php';

class ParticipantController {
    private $participantModel;

    public function __construct() {
        $this->participantModel = new Participant();
    }

    // Method untuk mengambil semua data peserta (bisa diberikan filter)
    public function getAll($filter = []) {
       $where = "";
       if (!empty($filter['bulan']) && !empty($filter['tahun'])) {
           $bulan = intval($filter['bulan']);
           $tahun = intval($filter['tahun']);
           $where = "WHERE MONTH(tanggal_pelaksanaan) = $bulan AND YEAR(tanggal_pelaksanaan) = $tahun";
       }
       $sql = "SELECT * FROM participants $where ORDER BY tanggal_pelaksanaan ASC, nomor ASC";
       $result = $this->conn->query($sql);
    
       $data = [];
       while ($row = $result->fetch_assoc()) {
          $data[] = $row;
       }
    
       return $data;
    }

    // Menampilkan form dan data peserta
    public function index() {
        $filter = [];
        if(isset($_GET['filter_bulan']) && isset($_GET['filter_tahun'])) {
            $filter['bulan'] = $_GET['filter_bulan'];
            $filter['tahun'] = $_GET['filter_tahun'];
        }
        $data['participants'] = $this->participantModel->getAll($filter);
        require_once __DIR__ . '/../views/participants/index.php';
    }
    
    // Menampilkan form input data peserta
    public function form() {
        require_once __DIR__ . '/../views/participants/form.php';
    }

    // Menyimpan data peserta
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = [];
            if(empty($_POST['nama_pasien'])){
                $errors[] = "Nama pasien wajib diisi.";
            }
            if(empty($_POST['nomor_kartu_bpjs']) || !preg_match('/^\d{13}$/', $_POST['nomor_kartu_bpjs'])) {
                $errors[] = "Nomor kartu BPJS harus berisi tepat 13 angka.";
            }
            if(empty($_POST['tinggi_badan'])){
                $errors[] = "Tinggi badan wajib diisi.";
            }
            if(empty($_POST['berat_badan'])){
                $errors[] = "Berat badan wajib diisi.";
            }
            if(empty($_POST['lingkar_perut'])){
                $errors[] = "Lingkar perut wajib diisi.";
            }
            if(empty($_POST['sistole'])){
                $errors[] = "Sistole harus diisi.";
            }
            if(empty($_POST['diastole'])){
                $errors[] = "Diastole harus diisi.";
            }
            if(empty($_POST['respiratory_rate'])){
                $errors[] = "Respiratory rate harus diisi.";
            }
            if(empty($_POST['heart_rate'])){
                $errors[] = "Heart rate harus diisi.";
            }

            if (!empty($errors)) {
               echo "<script>
                   Swal.fire({
                       icon: 'error',
                       title: 'Oops...',
                       html: '".implode("<br>", $errors)."',
                       confirmButtonColor: '#d33',
                       confirmButtonText: 'OK'
                   });
               </script>";
               require_once __DIR__ . '/../views/participants/form.php';
               return;
            }
            
            $result = $this->participantModel->save($_POST);
            if(isset($result['error'])) {
                $data['errors'] = [$result['error']];
                require_once __DIR__ . '/../views/participants/form.php';
            } else {
                header("Location: index.php?action=index");
                exit;
            }
        }
    }
    
    // Menghapus data peserta
    public function delete() {
        if(isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $result = $this->participantModel->delete($id);
            if($result) {
                header("Location: index.php?action=index");
                exit;
            } else {
                echo "Error saat menghapus data.";
            }
        } else {
            echo "Data tidak ditemukan.";
        }
    }

    // Edit data peserta
    public function edit() {
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $participant = $this->participantModel->findById($id);
            if ($participant) {
                $data['participant'] = $participant;
                require_once __DIR__ . '/../views/participants/form.php';
            } else {
                echo "Data tidak ditemukan.";
            }
        } else {
            echo "ID tidak ditemukan di parameter URL.";
        }
    }

    // Menampilkan halaman riwayat data berdasarkan filter bulan & tahun
    public function riwayat() {
        $filter = [];
        if (isset($_GET['filter_bulan']) && isset($_GET['filter_tahun'])) {
            $filter['bulan'] = $_GET['filter_bulan'];
            $filter['tahun'] = $_GET['filter_tahun'];
        }
        $data['participants'] = $this->participantModel->getAll($filter);
        require_once __DIR__ . '/../views/participants/riwayat.php';
    }

    // Menampilkan Cetak berdasarkan filter pada Riwayat data
    public function cetak() {
        $filter = [];
        if (isset($_GET['filter_bulan']) && isset($_GET['filter_tahun'])) {
            $filter['bulan'] = $_GET['filter_bulan'];
            $filter['tahun'] = $_GET['filter_tahun'];
        }
        $data['participants'] = $this->participantModel->getAll($filter);
        require_once __DIR__ . '/../views/participants/cetak.php';
    }

    // Menampilkan Update isian data
public function update() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        $result = $this->participantModel->update($id, $_POST);
        if ($result) {
            header("Location: index.php?action=index");
            exit;
        } else {
            $participant = $_POST;
            $participant['id'] = $id; // pastikan ID tetap ada
            $data['participant'] = $participant;
            $data['errors'] = ['Gagal memperbarui data.'];
            require_once __DIR__ . '/../views/participants/form.php';
        }
    }
}
}
?>

