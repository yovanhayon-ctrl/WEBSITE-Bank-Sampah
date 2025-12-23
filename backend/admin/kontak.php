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
    $stmt = $pdo->prepare("DELETE FROM kontak WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: kontak.php?deleted=1');
    exit;
}

// Get all kontak
$stmt = $pdo->query("SELECT * FROM kontak ORDER BY created_at DESC");
$kontak_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kontak - Admin Panel</title>
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
                <li>
                    <a href="pendaftaran.php"><i class="fas fa-users"></i> Pendaftaran Nasabah</a>
                </li>
                <li class="active">
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
                <h2>Data Pesan Kontak</h2>

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
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Subjek</th>
                                        <th>Pesan</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($kontak_list) > 0): ?>
                                        <?php $no = 1; foreach ($kontak_list as $kontak): ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo htmlspecialchars($kontak['nama']); ?></td>
                                            <td><?php echo htmlspecialchars($kontak['email']); ?></td>
                                            <td><?php echo htmlspecialchars($kontak['subjek']); ?></td>
                                            <td><?php echo htmlspecialchars(substr($kontak['pesan'], 0, 50)) . (strlen($kontak['pesan']) > 50 ? '...' : ''); ?></td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($kontak['created_at'])); ?></td>
                                            <td>
                                                <a href="#" class="btn btn-info btn-sm view-btn" 
                                                   data-nama="<?php echo htmlspecialchars($kontak['nama']); ?>" 
                                                   data-email="<?php echo htmlspecialchars($kontak['email']); ?>" 
                                                   data-subjek="<?php echo htmlspecialchars($kontak['subjek']); ?>" 
                                                   data-pesan="<?php echo htmlspecialchars($kontak['pesan']); ?>" 
                                                   data-tanggal="<?php echo date('d/m/Y H:i', strtotime($kontak['created_at'])); ?>" 
                                                   data-bs-toggle="modal" data-bs-target="#viewModal">
                                                    <i class="fas fa-eye"></i> Lihat
                                                </a>
                                                <a href="?action=delete&id=<?php echo $kontak['id']; ?>" 
                                                   class="btn btn-danger btn-sm" 
                                                   onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada pesan kontak</td>
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

    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">Detail Pesan Kontak</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nama:</strong> <span id="view_nama"></span></p>
                            <p><strong>Email:</strong> <span id="view_email"></span></p>
                            <p><strong>Subjek:</strong> <span id="view_subjek"></span></p>
                            <p><strong>Tanggal:</strong> <span id="view_tanggal"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Pesan:</strong></p>
                            <div id="view_pesan" class="border p-3 bg-light"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/admin.js"></script>
    <script>
        // Fill view modal with data
        document.querySelectorAll('.view-btn').forEach(button => {
            button.addEventListener('click', function() {
                const nama = this.getAttribute('data-nama');
                const email = this.getAttribute('data-email');
                const subjek = this.getAttribute('data-subjek');
                const pesan = this.getAttribute('data-pesan');
                const tanggal = this.getAttribute('data-tanggal');
                
                document.getElementById('view_nama').textContent = nama;
                document.getElementById('view_email').textContent = email;
                document.getElementById('view_subjek').textContent = subjek;
                document.getElementById('view_pesan').textContent = pesan;
                document.getElementById('view_tanggal').textContent = tanggal;
            });
        });
    </script>
</body>
</html>