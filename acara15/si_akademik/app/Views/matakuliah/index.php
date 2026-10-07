<?php $offset = ($pager['page'] - 1) * $pager['perPage']; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">Mata Kuliah</h1>
    <a href="<?= url('/matakuliah/create') ?>" class="btn btn-success">+ Tambah Mata Kuliah</a>
</div>
<p class="text-muted">Total <?= $pager['total'] ?> data</p>

<table class="table table-bordered table-striped bg-white">
    <thead class="table-dark">
        <tr>
            <th style="width:50px">No</th><th>Kode</th><th>Nama Mata Kuliah</th>
            <th>SKS</th><th>Prodi</th><th style="width:150px">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pager['data'] as $i => $mk): ?>
            <tr>
                <td><?= $offset + $i + 1 ?></td>
                <td><?= e($mk['kode']) ?></td>
                <td><?= e($mk['nama']) ?></td>
                <td><?= e($mk['sks']) ?></td>
                <td><?= e($mk['prodi_nama']) ?></td>
                <td>
                    <a href="<?= url('/matakuliah/' . $mk['id'] . '/edit') ?>" class="btn btn-warning btn-sm">Edit</a>
                    <form action="<?= url('/matakuliah/' . $mk['id'] . '/delete') ?>" method="POST" class="d-inline"
                          data-confirm="Yakin hapus mata kuliah <?= e($mk['nama']) ?>?">
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($pager['data'])): ?>
            <tr><td colspan="6" class="text-center text-muted">Belum ada data.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../partials/pagination.php'; ?>
