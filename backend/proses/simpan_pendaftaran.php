<?php
require_once '../config.php';

// Check if this is a frontend request (no admin session required for frontend)
$is_frontend_request = !isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $nik = trim($_POST['nik']);
    $no_whatsapp = trim($_POST['no_whatsapp']);
    $alamat = trim($_POST['alamat']);

    // Basic validation
    if (!empty($nama_lengkap) && !empty($nik) && !empty($no_whatsapp) && !empty($alamat)) {
        // Validate NIK (16 digits)
        if (!preg_match('/^\d{16}$/', $nik)) {
            if ($is_frontend_request) {
                echo json_encode(['status' => 'error', 'message' => 'NIK harus berupa 16 digit angka']);
                exit;
            } else {
                header('Location: ../admin/pendaftaran.php?error=nik');
                exit;
            }
        }

        // Validate phone number (Indonesian format)
        if (!preg_match('/^08\d{8,11}$/', $no_whatsapp)) {
            if ($is_frontend_request) {
                echo json_encode(['status' => 'error', 'message' => 'Nomor WhatsApp harus diawali dengan 08 dan berisi 10-13 digit']);
                exit;
            } else {
                header('Location: ../admin/pendaftaran.php?error=phone');
                exit;
            }
        }

        // Insert data
        $tanggal_daftar = date('Y-m-d');
        $stmt = $pdo->prepare("INSERT INTO pendaftaran (nama_lengkap, nik, no_whatsapp, alamat, tanggal_daftar) VALUES (?, ?, ?, ?, ?)");
        $result = $stmt->execute([$nama_lengkap, $nik, $no_whatsapp, $alamat, $tanggal_daftar]);

        if ($result) {
            if ($is_frontend_request) {
                echo json_encode(['status' => 'success', 'message' => 'Pendaftaran berhasil! Data telah dikirim.']);
                exit;
            } else {
                header('Location: ../admin/pendaftaran.php?success=1');
                exit;
            }
        } else {
            if ($is_frontend_request) {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data. Silakan coba lagi.']);
                exit;
            } else {
                header('Location: ../admin/pendaftaran.php?error=1');
                exit;
            }
        }
    } else {
        if ($is_frontend_request) {
            echo json_encode(['status' => 'error', 'message' => 'Silakan isi semua field!']);
            exit;
        } else {
            header('Location: ../admin/pendaftaran.php?error=2');
            exit;
        }
    }
}

// Redirect if accessed directly (only for admin panel)
if (!$is_frontend_request) {
    header('Location: ../admin/pendaftaran.php');
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Akses tidak sah']);
    exit;
}
?>