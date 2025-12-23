<?php
// Database setup file - create kontak table if it doesn't exist
require_once 'config.php';

try {
    // Check if kontak table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'kontak'");
    $tableExists = $stmt->rowCount();
    
    if ($tableExists == 0) {
        // Create kontak table
        $createTable = "CREATE TABLE kontak (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nama VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            subjek VARCHAR(200) NOT NULL,
            pesan TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $pdo->exec($createTable);
        echo "Tabel 'kontak' berhasil dibuat!<br>";
    } else {
        echo "Tabel 'kontak' sudah ada.<br>";
    }
    
    // Also check if admin user exists, if not create a default one
    $stmt = $pdo->query("SELECT COUNT(*) FROM admin");
    $adminCount = $stmt->fetchColumn();
    
    if ($adminCount == 0) {
        // Create default admin user (username: admin, password: admin123)
        $defaultPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
        $stmt->execute(['admin', $defaultPassword]);
        echo "Akun admin default dibuat (username: admin, password: admin123)<br>";
    } else {
        echo "Akun admin sudah ada.<br>";
    }
    
    echo "Setup selesai. Silakan login ke admin panel.";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>