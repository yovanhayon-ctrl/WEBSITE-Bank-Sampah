<?php
require_once '../config.php';

// Check if this is a frontend request (no admin session required for frontend)
$is_frontend_request = !isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $subjek = trim($_POST['subjek']);
    $pesan = trim($_POST['pesan']);

    // Basic validation
    if (!empty($nama) && !empty($email) && !empty($subjek) && !empty($pesan)) {
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($is_frontend_request) {
                echo json_encode(['status' => 'error', 'message' => 'Format email tidak valid']);
                exit;
            } else {
                header('Location: ../admin/kontak.php?error=email');
                exit;
            }
        }

        // Insert data
        $stmt = $pdo->prepare("INSERT INTO kontak (nama, email, subjek, pesan) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute([$nama, $email, $subjek, $pesan]);

        if ($result) {
            if ($is_frontend_request) {
                echo json_encode(['status' => 'success', 'message' => 'Pesan berhasil dikirim! Kami akan segera menghubungi Anda.']);
                exit;
            } else {
                header('Location: ../admin/kontak.php?success=1');
                exit;
            }
        } else {
            if ($is_frontend_request) {
                echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim pesan. Silakan coba lagi.']);
                exit;
            } else {
                header('Location: ../admin/kontak.php?error=1');
                exit;
            }
        }
    } else {
        if ($is_frontend_request) {
            echo json_encode(['status' => 'error', 'message' => 'Silakan isi semua field!']);
            exit;
        } else {
            header('Location: ../admin/kontak.php?error=2');
            exit;
        }
    }
}

// Redirect if accessed directly (only for admin panel)
if (!$is_frontend_request) {
    header('Location: ../admin/kontak.php');
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Akses tidak sah']);
    exit;
}
?>