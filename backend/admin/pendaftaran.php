<?php
require_once '../config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../auth/login.php');
    exit;
}

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM pendaftaran WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: pendaftaran.php?deleted=1');
    exit;
}

// Get all pendaftaran
$stmt = $pdo->query("SELECT * FROM pendaftaran ORDER BY tanggal_daftar DESC");
$pendaftaran_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pendaftaran - Admin Panel</title>
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
                <li>
                    <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
                </li>
                <li>
                    <a href="jenis_sampah.php"><i class="fas fa-recycle"></i> Jenis Sampah</a>
                </li>
                <li class="active">
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
                <h2>Data Pendaftaran Nasabah</h2>

                <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
                    <div class="alert alert-success mt-3">Data berhasil dihapus!</div>
                <?php endif; ?>

                <div class="card mt-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Lengkap</th>
                                        <th>NIK</th>
                                        <th>No WhatsApp</th>
                                        <th>Alamat</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($pendaftaran_list) > 0): ?>
                                        <?php $no = 1; foreach ($pendaftaran_list as $pendaftaran): ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo htmlspecialchars($pendaftaran['nama_lengkap']); ?></td>
                                            <td><?php echo htmlspecialchars($pendaftaran['nik']); ?></td>
                                            <td><?php echo htmlspecialchars($pendaftaran['no_whatsapp']); ?></td>
                                            <td><?php echo htmlspecialchars($pendaftaran['alamat']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($pendaftaran['tanggal_daftar'])); ?></td>
                                            <td>
                                                <a href="?action=delete&id=<?php echo $pendaftaran['id']; ?>" 
                                                   class="btn btn-danger btn-sm" 
                                                   onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data pendaftaran</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
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