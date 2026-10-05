<?php
$badge  = ['aktif' => 'success', 'cuti' => 'warning', 'lulus' => 'secondary'];
$offset = ($pager['page'] - 1) * $pager['perPage'];
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">Daftar Mahasiswa</h1>
    <a href="<?= url('/mahasiswa/create') ?>" class="btn btn-success">+ Tambah Mahasiswa</a>
</div>

<!-- Pencarian (Tugas Mandiri): nama atau NIM -->
<form method="GET" action="<?= url('/mahasiswa') ?>" class="row g-2 mb-3">
    <div class="col-md-5">
        <input type="text" name="q" value="<?= e($search) ?>" class="form-control" placeholder="Cari nama atau NIM...">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Cari</button>
        <?php if ($search !== ''): ?>
            <a href="<?= url('/mahasiswa') ?>" class="btn btn-outline-secondary">Reset</a>
        <?php endif; ?>
    </div>
</form>

<p class="text-muted">
    <?php if ($search !== ''): ?>
        Hasil pencarian untuk "<strong><?= e($search) ?></strong>": <?= $pager['total'] ?> data
    <?php else: ?>
        Total <?= $pager['total'] ?> data
    <?php endif; ?>
</p>

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
            <th style="width:150px">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pager['data'] as $i => $m): ?>
            <tr>
                <td><?= $offset + $i + 1 ?></td>
                <td><?= e($m['nim']) ?></td>
                <td><?= e($m['nama']) ?></td>
                <td><?= e($m['email']) ?></td>
                <td><?= e($m['prodi_nama']) ?></td>
                <td><?= e($m['angkatan']) ?></td>
                <td><span class="badge text-bg-<?= $badge[$m['status']] ?? 'secondary' ?>"><?= e($m['status']) ?></span></td>
                <td>
                    <a href="<?= url('/mahasiswa/' . $m['id'] . '/edit') ?>" class="btn btn-warning btn-sm">Edit</a>
                    <form action="<?= url('/mahasiswa/' . $m['id'] . '/delete') ?>" method="POST" class="d-inline"
                          data-confirm="Yakin hapus mahasiswa <?= e($m['nama']) ?>?">
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($pager['data'])): ?>
            <tr><td colspan="8" class="text-center text-muted">Data tidak ditemukan.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../partials/pagination.php'; ?>
