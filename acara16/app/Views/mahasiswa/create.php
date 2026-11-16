<h1 class="mb-4"><?= $isEditing ? 'Edit Mahasiswa' : 'Tambah Mahasiswa' ?></h1>

<form action="<?= htmlspecialchars(BASE_URL) ?><?= $isEditing ? '/mahasiswa/edit' : '/mahasiswa' ?>" method="POST">
  <?php if ($isEditing): ?>
    <input type="hidden" name="id" value="<?= (int) $mahasiswa['id'] ?>">
  <?php endif; ?>
  <div class="mb-3">
    <label for="nim" class="form-label">NIM</label>
    <input type="text" class="form-control" id="nim" name="nim" value="<?= htmlspecialchars($mahasiswa['nim'] ?? '') ?>" required>
  </div>
  <div class="mb-3">
    <label for="nama" class="form-label">Nama</label>
    <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($mahasiswa['nama'] ?? '') ?>" required>
  </div>
  <div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($mahasiswa['email'] ?? '') ?>" required>
  </div>
  <div class="mb-3">
    <label for="prodi_id" class="form-label">Program Studi</label>
    <select class="form-select" id="prodi_id" name="prodi_id" required>
      <option value="">Pilih program studi</option>
      <?php foreach ($prodiList as $prodi): ?>
        <option value="<?= (int) $prodi['id'] ?>" <?= (int) ($mahasiswa['prodi_id'] ?? 0) === (int) $prodi['id'] ? 'selected' : '' ?>><?= htmlspecialchars($prodi['nama']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3">
    <label for="angkatan" class="form-label">Angkatan</label>
    <input type="number" class="form-control" id="angkatan" name="angkatan" min="1901" max="2155" value="<?= htmlspecialchars((string) ($mahasiswa['angkatan'] ?? '')) ?>" required>
  </div>
  <div class="mb-3">
    <label for="status" class="form-label">Status</label>
    <select class="form-select" id="status" name="status" required>
      <?php foreach (['aktif' => 'Aktif', 'cuti' => 'Cuti', 'lulus' => 'Lulus'] as $value => $label): ?>
        <option value="<?= $value ?>" <?= ($mahasiswa['status'] ?? 'aktif') === $value ? 'selected' : '' ?>><?= $label ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <button type="submit" class="btn btn-primary">Simpan</button>
  <a href="<?= htmlspecialchars(BASE_URL) ?>/mahasiswa" class="btn btn-secondary">Batal</a>
</form>
