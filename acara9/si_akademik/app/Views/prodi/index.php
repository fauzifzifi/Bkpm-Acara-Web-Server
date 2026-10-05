<?php $offset = ($pager['page'] - 1) * $pager['perPage']; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">Program Studi</h1>
    <a href="<?= url('/prodi/create') ?>" class="btn btn-success">+ Tambah Prodi</a>
</div>
<p class="text-muted">Total <?= $pager['total'] ?> data</p>

<table class="table table-bordered table-striped bg-white">
    <thead class="table-dark">
        <tr><th style="width:50px">No</th><th>Kode</th><th>Nama Program Studi</th><th style="width:150px">Aksi</th></tr>
    </thead>
    <tbody>
        <?php foreach ($pager['data'] as $i => $p): ?>
            <tr>
                <td><?= $offset + $i + 1 ?></td>
                <td><?= e($p['kode']) ?></td>
                <td><?= e($p['nama']) ?></td>
                <td>
                    <a href="<?= url('/prodi/' . $p['id'] . '/edit') ?>" class="btn btn-warning btn-sm">Edit</a>
                    <form action="<?= url('/prodi/' . $p['id'] . '/delete') ?>" method="POST" class="d-inline"
                          data-confirm="Yakin hapus prodi <?= e($p['nama']) ?>?">
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($pager['data'])): ?>
            <tr><td colspan="4" class="text-center text-muted">Belum ada data.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../partials/pagination.php'; ?>
