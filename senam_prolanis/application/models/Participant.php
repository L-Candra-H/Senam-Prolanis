<?php
// File: application/models/Participant.php
require_once __DIR__ . '/../config/config.php';

class Participant {
    private $conn;

    public function __construct() {
        $this->conn = get_db_connection();
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

    // Method untuk mencari peserta berdasarkan ID
    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM participants WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Method untuk menyimpan data peserta baru
    public function save($data) {
        $tanggal   = $this->conn->real_escape_string($data['tanggal_pelaksanaan']);
        $nama      = $this->conn->real_escape_string($data['nama_pasien']);
        $bpjs      = $this->conn->real_escape_string($data['nomor_kartu_bpjs']);
        $tinggi    = intval($data['tinggi_badan']);
        $berat     = intval($data['berat_badan']);
        $lingkar   = intval($data['lingkar_perut']);
        $sistole   = intval($data['sistole']);
        $diastole  = intval($data['diastole']);
        $respRate  = intval($data['respiratory_rate']);
        $heartRate = intval($data['heart_rate']);

        // Validasi Nomor BPJS agar tidak duplikat di tanggal yang sama
        $stmtCheckBPJS = $this->conn->prepare("SELECT id FROM participants WHERE nomor_kartu_bpjs = ? AND tanggal_pelaksanaan = ?");
        $stmtCheckBPJS->bind_param("ss", $bpjs, $tanggal);
        $stmtCheckBPJS->execute();
        $stmtCheckBPJS->store_result();
    
        if ($stmtCheckBPJS->num_rows > 0) {
            return ['error' => 'Nomor Kartu BPJS sudah terdaftar untuk tanggal yang sama!'];
        }

        // Validasi kuota peserta maksimal 30 orang per tanggal
        $stmtKuota = $this->conn->prepare("SELECT COUNT(*) AS jumlah FROM participants WHERE tanggal_pelaksanaan = ?");
        $stmtKuota->bind_param("s", $tanggal);
        $stmtKuota->execute();
        $stmtKuota->store_result();
        $stmtKuota->bind_result($jumlahPeserta);
        $stmtKuota->fetch();

        if ($jumlahPeserta >= 30) {
            return ['error' => 'Data peserta untuk tanggal ini sudah mencapai batas maksimal (30 peserta).'];
        }

        // Menentukan nomor peserta terbaru untuk tanggal tersebut
        $stmtNomor = $this->conn->prepare("SELECT MAX(nomor) AS max_nomor FROM participants WHERE tanggal_pelaksanaan = ?");
        $stmtNomor->bind_param("s", $tanggal);
        $stmtNomor->execute();
        $stmtNomor->store_result();
        $stmtNomor->bind_result($max_nomor);
        $stmtNomor->fetch();
        $nomor = intval($max_nomor) + 1;

        // Simpan data peserta baru
        $stmtInsert = $this->conn->prepare("INSERT INTO participants (
                    nomor, tanggal_pelaksanaan, nama_pasien, nomor_kartu_bpjs, 
                    tinggi_badan, berat_badan, lingkar_perut, sistole, 
                    diastole, respiratory_rate, heart_rate) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtInsert->bind_param("isssiiiiiii", $nomor, $tanggal, $nama, $bpjs, $tinggi, $berat, $lingkar, $sistole, $diastole, $respRate, $heartRate);

        return $stmtInsert->execute() ? ['success' => true] : ['error' => $this->conn->error];
    }

    // Method untuk memperbarui data peserta
    public function update($id, $data) {
        $id        = intval($id);
        $tanggal   = $this->conn->real_escape_string($data['tanggal_pelaksanaan']);
        $nama      = $this->conn->real_escape_string($data['nama_pasien']);
        $bpjs      = $this->conn->real_escape_string($data['nomor_kartu_bpjs']);

        // Cek apakah Nomor BPJS sudah ada, kecuali dirinya sendiri
        $stmtCheckBPJS = $this->conn->prepare("SELECT id FROM participants WHERE nomor_kartu_bpjs = ? AND tanggal_pelaksanaan = ? AND id != ?");
        $stmtCheckBPJS->bind_param("ssi", $bpjs, $tanggal, $id);
        $stmtCheckBPJS->execute();
        $stmtCheckBPJS->store_result();

        if ($stmtCheckBPJS->num_rows > 0) {
            return ['error' => 'Nomor Kartu BPJS sudah terdaftar untuk tanggal yang sama!'];
        }

        // Update data peserta
        $stmtUpdate = $this->conn->prepare("UPDATE participants SET
                tanggal_pelaksanaan = ?, 
                nama_pasien = ?, 
                nomor_kartu_bpjs = ?
            WHERE id = ?");
        $stmtUpdate->bind_param("sssi", $tanggal, $nama, $bpjs, $id);

        return $stmtUpdate->execute();
    }

    // Method untuk menghapus data peserta berdasarkan id
    public function delete($id) {
        $id = intval($id);
        $stmt = $this->conn->prepare("DELETE FROM participants WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>

