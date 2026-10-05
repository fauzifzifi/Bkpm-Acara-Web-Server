<?php
$badge = ['aktif' => 'success', 'cuti' => 'warning', 'lulus' => 'secondary'];
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">Daftar Mahasiswa</h1>
    <a href="<?= url('/mahasiswa/create') ?>" class="btn btn-success">+ Tambah Mahasiswa</a>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
<?php endif; ?>

<table class="table table-bordered table-striped bg-white">
    <thead class="table-dark">
        <tr>
            <th style="width:50px">No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Prodi</th>
            <th>Angkatan</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($daftarMahasiswa as $i => $mhs): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= e($mhs['nim']) ?></td>
                <td><?= e($mhs['nama']) ?></td>
                <td><?= e($mhs['email']) ?></td>
                <td><?= e($mhs['prodi']) ?></td>
                <td><?= e($mhs['angkatan']) ?></td>
                <td><span class="badge text-bg-<?= $badge[$mhs['status']] ?? 'secondary' ?>"><?= e($mhs['status']) ?></span></td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$error && empty($daftarMahasiswa)): ?>
            <tr><td colspan="7" class="text-center text-muted">Belum ada data.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
