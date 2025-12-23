<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../backend/config.php';

try {
    $stmt = $pdo->query("SELECT nama_sampah, harga_per_kg FROM jenis_sampah WHERE 1 ORDER BY nama_sampah ASC");
    $jenis_sampah_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return the data as JSON
    echo json_encode(['status' => 'success', 'data' => $jenis_sampah_list]);
} catch(PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>