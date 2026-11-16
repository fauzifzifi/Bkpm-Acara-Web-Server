<h1 class="mb-4"><?= $isEditing ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah' ?></h1>
<form action="<?= htmlspecialchars(BASE_URL) ?>/matakuliah<?= $isEditing ? '/' . (int) $matakuliah['id'] : '' ?>" method="POST">
  <div class="mb-3">
    <label for="kode" class="form-label">Kode</label>
    <input type="text" class="form-control" id="kode" name="kode" maxlength="10" value="<?= htmlspecialchars($matakuliah['kode'] ?? '') ?>" required>
  </div>
  <div class="mb-3">
    <label for="nama" class="form-label">Nama Mata Kuliah</label>
    <input type="text" class="form-control" id="nama" name="nama" maxlength="150" value="<?= htmlspecialchars($matakuliah['nama'] ?? '') ?>" required>
  </div>
  <div class="mb-3">
    <label for="sks" class="form-label">SKS</label>
    <input type="number" class="form-control" id="sks" name="sks" min="1" max="255" value="<?= htmlspecialchars((string) ($matakuliah['sks'] ?? '')) ?>" required>
  </div>
  <div class="mb-3">
    <label for="prodi_id" class="form-label">Program Studi</label>
    <select class="form-select" id="prodi_id" name="prodi_id" required>
      <option value="">Pilih program studi</option>
      <?php foreach ($prodiList as $prodi): ?>
        <option value="<?= (int) $prodi['id'] ?>" <?= (int) ($matakuliah['prodi_id'] ?? 0) === (int) $prodi['id'] ? 'selected' : '' ?>><?= htmlspecialchars($prodi['nama']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <button type="submit" class="btn btn-primary">Simpan</button>
  <a href="<?= htmlspecialchars(BASE_URL) ?>/matakuliah" class="btn btn-secondary">Batal</a>
</form>
