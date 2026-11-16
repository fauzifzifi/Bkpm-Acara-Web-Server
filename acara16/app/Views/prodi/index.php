<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="mb-0">Program Studi</h1>
  <a href="<?= htmlspecialchars(BASE_URL) ?>/prodi/create" class="btn btn-primary">Tambah Prodi</a>
</div>
<table class="table table-bordered table-striped">
  <thead class="table-dark">
    <tr><th>Kode</th><th>Nama Program Studi</th><th>Aksi</th></tr>
  </thead>
  <tbody>
    <?php foreach ($prodiList as $prodi): ?>
      <tr>
        <td><?= htmlspecialchars($prodi['kode']) ?></td>
        <td><?= htmlspecialchars($prodi['nama']) ?></td>
        <td>
          <div class="d-flex gap-1">
            <a href="<?= htmlspecialchars(BASE_URL) ?>/prodi/<?= (int) $prodi['id'] ?>/edit" class="btn btn-sm btn-warning">Edit</a>
            <form action="<?= htmlspecialchars(BASE_URL) ?>/prodi/<?= (int) $prodi['id'] ?>/delete" method="POST" onsubmit="return confirm('Hapus program studi ini?')">
              <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
            </form>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php if ($prodiList === []): ?><tr><td colspan="3" class="text-center text-muted">Belum ada data program studi.</td></tr><?php endif; ?>
  </tbody>
</table>
