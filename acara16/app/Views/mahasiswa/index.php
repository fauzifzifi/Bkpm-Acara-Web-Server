<h1 class="mb-4">Daftar Mahasiswa</h1>
<div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
  <a href="<?= htmlspecialchars(BASE_URL) ?>/mahasiswa/create" class="btn btn-primary">Tambah Mahasiswa</a>
  <form action="<?= htmlspecialchars(BASE_URL) ?>/mahasiswa" method="GET" class="d-flex gap-2">
    <input type="search" name="q" class="form-control" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama atau NIM" aria-label="Cari nama atau NIM">
    <button type="submit" class="btn btn-outline-primary">Cari</button>
    <?php if ($search !== ''): ?><a href="<?= htmlspecialchars(BASE_URL) ?>/mahasiswa" class="btn btn-outline-secondary">Reset</a><?php endif; ?>
  </form>
</div>
<table class="table table-bordered table-striped">
  <thead class="table-dark">
    <tr>
      <th>NIM</th>
      <th>Nama</th>
      <th>Prodi</th>
      <th>Angkatan</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($mahasiswaList as $mhs): ?>
    <tr>
      <td><?= htmlspecialchars($mhs['nim']) ?></td>
      <td><?= htmlspecialchars($mhs['nama']) ?></td>
      <td><?= htmlspecialchars($mhs['prodi'] ?? '') ?></td>
      <td><?= htmlspecialchars((string) $mhs['angkatan']) ?></td>
      <td>
        <div class="d-flex flex-wrap gap-1">
          <a href="<?= htmlspecialchars(BASE_URL) ?>/mahasiswa/<?= (int) $mhs['id'] ?>" class="btn btn-sm btn-info">Detail</a>
          <a href="<?= htmlspecialchars(BASE_URL) ?>/mahasiswa/edit?id=<?= (int) $mhs['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
          <form action="<?= htmlspecialchars(BASE_URL) ?>/mahasiswa/delete" method="POST" onsubmit="return confirm('Hapus data mahasiswa ini?')">
            <input type="hidden" name="id" value="<?= (int) $mhs['id'] ?>">
            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
          </form>
        </div>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if ($mahasiswaList === []): ?>
    <tr><td colspan="5" class="text-center text-muted">Data mahasiswa tidak ditemukan.</td></tr>
    <?php endif; ?>
  </tbody>
</table>
