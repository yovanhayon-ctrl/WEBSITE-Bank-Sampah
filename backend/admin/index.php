<?php
require_once '../config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../auth/login.php');
    exit;
}

// Get counts for dashboard
$stmt = $pdo->query("SELECT COUNT(*) FROM pendaftaran");
$total_nasabah = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM jenis_sampah");
$total_sampah = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM kontak");
$total_kontak = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Bank Sampah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="bg-success text-white">
            <div class="sidebar-header">
                <h3>Admin Panel</h3>
            </div>
            <ul class="list-unstyled components">
                <li class="active">
                    <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
                </li>
                <li>
                    <a href="jenis_sampah.php"><i class="fas fa-recycle"></i> Jenis Sampah</a>
                </li>
                <li>
                    <a href="pendaftaran.php"><i class="fas fa-users"></i> Pendaftaran Nasabah</a>
                </li>
                <li>
                    <a href="kontak.php"><i class="fas fa-envelope"></i> Pesan Kontak</a>
                </li>
                <li>
                    <a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-success">
                        <i class="fas fa-bars"></i>
                    </button>
                    <span class="navbar-text">Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong></span>
                </div>
            </nav>

            <div class="container-fluid mt-4">
                <h2>Dashboard Admin</h2>
                <p>Selamat datang di panel administrasi Bank Sampah</p>

                <!-- Stats Cards -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4><?php echo $total_nasabah; ?></h4>
                                        <p class="card-text">Nasabah Terdaftar</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-info mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4><?php echo $total_sampah; ?></h4>
                                        <p class="card-text">Jenis Sampah</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-recycle fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-warning mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4><?php echo $total_kontak; ?></h4>
                                        <p class="card-text">Pesan Kontak</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-envelope fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Data Nasabah Terbaru</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                $stmt = $pdo->query("SELECT * FROM pendaftaran ORDER BY tanggal_daftar DESC LIMIT 5");
                                $nasabah_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                
                                if (count($nasabah_list) > 0):
                                ?>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>No WhatsApp</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($nasabah_list as $nasabah): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($nasabah['nama_lengkap']); ?></td>
                                            <td><?php echo htmlspecialchars($nasabah['no_whatsapp']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($nasabah['tanggal_daftar'])); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php else: ?>
                                <p class="text-muted">Tidak ada data nasabah</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Jenis Sampah Terbaru</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                $stmt = $pdo->query("SELECT * FROM jenis_sampah ORDER BY created_at DESC LIMIT 5");
                                $sampah_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                
                                if (count($sampah_list) > 0):
                                ?>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama Sampah</th>
                                            <th>Harga/Kg</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($sampah_list as $sampah): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($sampah['nama_sampah']); ?></td>
                                            <td>Rp <?php echo number_format($sampah['harga_per_kg']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($sampah['created_at'])); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php else: ?>
                                <p class="text-muted">Tidak ada data jenis sampah</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/admin.js"></script>
</body>
</html>