<?php
// ==================================================
// KONFIGURASI DATABASE DENGAN AUTO-INITIALIZATION
// ==================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'promethee_db');

// Buat koneksi ke MySQL server
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

if ($conn->connect_error) {
    die("Koneksi MySQL server gagal: " . $conn->connect_error);
}

// Cek apakah database promethee_db sudah ada, jika belum buat otomatis
$db_check = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . DB_NAME . "'");
if ($db_check->num_rows == 0) {
    $conn->query("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
}

// Pilih database
$conn->select_db(DB_NAME);
$conn->set_charset("utf8mb4");

// Fungsi untuk memastikan tabel dan data awal tersedia
function initDatabaseIfNeeded($conn) {
    $checkTable = $conn->query("SHOW TABLES LIKE 'alternatif'");
    if (!$checkTable || $checkTable->num_rows == 0) {
        $sqlPath = __DIR__ . '/promethee_db.sql';
        if (file_exists($sqlPath)) {
            $sqlContent = file_get_contents($sqlPath);
            if ($conn->multi_query($sqlContent)) {
                do {
                    if ($res = $conn->store_result()) {
                        $res->free();
                    }
                } while ($conn->more_results() && $conn->next_result());
            }
        }
    }
}

// Jalankan inisialisasi jika diperlukan
initDatabaseIfNeeded($conn);

// ==================================================
// FUNGSI HELPER DATABASE
// ==================================================

/**
 * Ambil semua alternatif
 */
function getAllAlternatif($conn) {
    $result = $conn->query("SELECT * FROM alternatif ORDER BY id ASC");
    $data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[$row['id']] = $row;
        }
    }
    return $data;
}

/**
 * Ambil semua kriteria
 */
function getAllKriteria($conn) {
    $result = $conn->query("SELECT * FROM kriteria ORDER BY id ASC");
    $data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[$row['id']] = $row;
        }
    }
    return $data;
}

/**
 * Ambil nilai penilaian dalam bentuk matrix
 * Format: $matrix[id_kriteria][id_alternatif] = nilai
 */
function getPenilaianMatrix($conn) {
    $result = $conn->query("SELECT * FROM penilaian ORDER BY id_kriteria ASC, id_alternatif ASC");
    $matrix = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $matrix[$row['id_kriteria']][$row['id_alternatif']] = (int)$row['nilai'];
        }
    }
    return $matrix;
}

/**
 * Ambil nilai penilaian untuk satu alternatif
 */
function getPenilaianByAlternatif($conn, $id_alternatif) {
    $stmt = $conn->prepare("SELECT * FROM penilaian WHERE id_alternatif = ? ORDER BY id_kriteria ASC");
    $stmt->bind_param("i", $id_alternatif);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[$row['id_kriteria']] = (int)$row['nilai'];
    }
    return $data;
}

/**
 * Update nilai penilaian
 */
function updatePenilaian($conn, $id_alternatif, $id_kriteria, $nilai) {
    $nilai = max(1, min(10, (int)$nilai));
    $stmt = $conn->prepare("
        INSERT INTO penilaian (id_alternatif, id_kriteria, nilai) 
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE nilai = ?
    ");
    $stmt->bind_param("iiii", $id_alternatif, $id_kriteria, $nilai, $nilai);
    return $stmt->execute();
}

/**
 * Hapus semua penilaian
 */
function resetPenilaian($conn) {
    return $conn->query("DELETE FROM penilaian");
}

/**
 * Reset data ke default dari SQL
 */
function resetToDefault($conn) {
    $sqlPath = __DIR__ . '/promethee_db.sql';
    if (file_exists($sqlPath)) {
        $sqlContent = file_get_contents($sqlPath);
        if ($conn->multi_query($sqlContent)) {
            do {
                if ($res = $conn->store_result()) {
                    $res->free();
                }
            } while ($conn->more_results() && $conn->next_result());
        }
        return true;
    }
    return false;
}

/**
 * Cek apakah data penilaian sudah lengkap
 */
function isPenilaianComplete($conn) {
    $alternatif = getAllAlternatif($conn);
    $kriteria = getAllKriteria($conn);
    
    if (empty($alternatif) || empty($kriteria)) {
        return false;
    }
    
    $totalExpected = count($alternatif) * count($kriteria);
    
    $result = $conn->query("SELECT COUNT(*) as total FROM penilaian");
    if (!$result) return false;
    $row = $result->fetch_assoc();
    
    return ((int)$row['total'] >= $totalExpected);
}
?>
