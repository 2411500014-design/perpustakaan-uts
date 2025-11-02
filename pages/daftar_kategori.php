<?php
if (!defined('MY_APP')) {
    die('Akses langsung tidak diperbolehkan!');
}

if (!isset($mysqli)) {
    die('Koneksi database tidak tersedia.');
}

$sql = "SELECT * FROM kategori ORDER BY id_kategori DESC";
$result = $mysqli->query($sql);

if (!$result) {
    die("QUERY Error: " . $mysqli->error);
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Kategori</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Tambah Kategori</li>
    </ol>

    <div class="card mb-4">
        <div class="card-body">
            <a href="index.php?hal=tambah_kategori" class="btn btn-success mb-3">Tambah Kategori</a>

            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php $no = 1; ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                <td>
                                    <a href="index.php?hal=ubah_kategori&id=<?= $row['id_kategori'] ?>" class="btn btn-warning btn-sm">Ubah</a>
                                </td>
                            </tr>
                            <?php $no++; ?>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">Belum ada data kategori.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>