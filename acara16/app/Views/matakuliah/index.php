<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="mb-0">Mata Kuliah</h1>
  <a href="<?= htmlspecialchars(BASE_URL) ?>/matakuliah/create" class="btn btn-primary">Tambah Mata Kuliah</a>
</div>
<table class="table table-bordered table-striped">
  <thead class="table-dark">
    <tr><th>Kode</th><th>Nama Mata Kuliah</th><th>SKS</th><th>Program Studi</th><th>Aksi</th></tr>
  </thead>
  <tbody>
    <?php foreach ($matakuliahList as $matakuliah): ?>
      <tr>
        <td><?= htmlspecialchars($matakuliah['kode']) ?></td>
        <td><?= htmlspecialchars($matakuliah['nama']) ?></td>
        <td><?= (int) $matakuliah['sks'] ?></td>
        <td><?= htmlspecialchars($matakuliah['prodi'] ?? '') ?></td>
        <td>
          <div class="d-flex gap-1">
            <a href="<?= htmlspecialchars(BASE_URL) ?>/matakuliah/<?= (int) $matakuliah['id'] ?>/edit" class="btn btn-sm btn-warning">Edit</a>
            <form action="<?= htmlspecialchars(BASE_URL) ?>/matakuliah/<?= (int) $matakuliah['id'] ?>/delete" method="POST" onsubmit="return confirm('Hapus mata kuliah ini?')">
              <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
            </form>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php if ($matakuliahList === []): ?><tr><td colspan="5" class="text-center text-muted">Belum ada data mata kuliah.</td></tr><?php endif; ?>
  </tbody>
</table>
