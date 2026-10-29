-- File: sql/create_database.sql

-- Membuat database
CREATE DATABASE IF NOT EXISTS senam_prolanis;
USE senam_prolanis;

-- Tabel participants
CREATE TABLE participants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nomor INT,  -- nomor urut, otomatis terisi (maksimal 30 per tanggal)
    nama_pasien VARCHAR(100) NOT NULL,
    nomor_kartu_bpjs VARCHAR(13) NOT NULL,
    tanggal_pelaksanaan DATE NOT NULL,  -- format: YYYY-MM-DD
    tinggi_badan INT NOT NULL,
    berat_badan INT NOT NULL,
    lingkar_perut INT NOT NULL,
    sistole INT NOT NULL,
    diastole INT NOT NULL,
    respiratory_rate INT NOT NULL,
    heart_rate INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
