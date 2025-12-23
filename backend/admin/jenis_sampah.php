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
    $stmt = $pdo->prepare("DELETE FROM jenis_sampah WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: jenis_sampah.php?deleted=1');
    exit;
}

// Get all jenis sampah
$stmt = $pdo->query("SELECT * FROM jenis_sampah ORDER BY nama_sampah ASC");
$jenis_sampah_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jenis Sampah - Admin Panel</title>
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
                <li class="active">
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
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Data Jenis Sampah</h2>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fas fa-plus"></i> Tambah Jenis Sampah
                    </button>
                </div>

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
                                        <th>Nama Sampah</th>
                                        <th>Harga per Kg</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($jenis_sampah_list) > 0): ?>
                                        <?php $no = 1; foreach ($jenis_sampah_list as $sampah): ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo htmlspecialchars($sampah['nama_sampah']); ?></td>
                                            <td>Rp <?php echo number_format($sampah['harga_per_kg']); ?></td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($sampah['created_at'])); ?></td>
                                            <td>
                                                <a href="#" class="btn btn-warning btn-sm edit-btn" 
                                                   data-id="<?php echo $sampah['id']; ?>" 
                                                   data-nama="<?php echo htmlspecialchars($sampah['nama_sampah']); ?>" 
                                                   data-harga="<?php echo $sampah['harga_per_kg']; ?>" 
                                                   data-bs-toggle="modal" data-bs-target="#editModal">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="?action=delete&id=<?php echo $sampah['id']; ?>" 
                                                   class="btn btn-danger btn-sm" 
                                                   onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data jenis sampah</td>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Jenis Sampah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../proses/simpan_jenis_sampah.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_sampah" class="form-label">Nama Sampah</label>
                            <input type="text" class="form-control" id="nama_sampah" name="nama_sampah" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga_per_kg" class="form-label">Harga per Kg (Rp)</label>
                            <input type="number" class="form-control" id="harga_per_kg" name="harga_per_kg" required min="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success" name="action" value="add">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Jenis Sampah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../proses/simpan_jenis_sampah.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_nama_sampah" class="form-label">Nama Sampah</label>
                            <input type="text" class="form-control" id="edit_nama_sampah" name="nama_sampah" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_harga_per_kg" class="form-label">Harga per Kg (Rp)</label>
                            <input type="number" class="form-control" id="edit_harga_per_kg" name="harga_per_kg" required min="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success" name="action" value="edit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/admin.js"></script>
    <script>
        // Fill edit modal with data
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                const harga = this.getAttribute('data-harga');
                
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_nama_sampah').value = nama;
                document.getElementById('edit_harga_per_kg').value = harga;
            });
        });
    </script>
</body>
</html>