<?php
require_once '../config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'add') {
        $nama_sampah = trim($_POST['nama_sampah']);
        $harga_per_kg = (int)$_POST['harga_per_kg'];
        
        if (!empty($nama_sampah) && $harga_per_kg >= 0) {
            $stmt = $pdo->prepare("INSERT INTO jenis_sampah (nama_sampah, harga_per_kg) VALUES (?, ?)");
            $result = $stmt->execute([$nama_sampah, $harga_per_kg]);
            
            if ($result) {
                header('Location: ../admin/jenis_sampah.php?success=1');
                exit;
            } else {
                header('Location: ../admin/jenis_sampah.php?error=1');
                exit;
            }
        } else {
            header('Location: ../admin/jenis_sampah.php?error=2');
            exit;
        }
    } 
    elseif ($action == 'edit') {
        $id = (int)$_POST['id'];
        $nama_sampah = trim($_POST['nama_sampah']);
        $harga_per_kg = (int)$_POST['harga_per_kg'];
        
        if (!empty($nama_sampah) && $harga_per_kg >= 0) {
            $stmt = $pdo->prepare("UPDATE jenis_sampah SET nama_sampah = ?, harga_per_kg = ? WHERE id = ?");
            $result = $stmt->execute([$nama_sampah, $harga_per_kg, $id]);
            
            if ($result) {
                header('Location: ../admin/jenis_sampah.php?success=2');
                exit;
            } else {
                header('Location: ../admin/jenis_sampah.php?error=1');
                exit;
            }
        } else {
            header('Location: ../admin/jenis_sampah.php?error=2');
            exit;
        }
    }
}

// Redirect if accessed directly
header('Location: ../admin/jenis_sampah.php');
exit;
?>