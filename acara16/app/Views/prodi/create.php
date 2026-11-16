<h1 class="mb-4"><?= $isEditing ? 'Edit Program Studi' : 'Tambah Program Studi' ?></h1>
<form action="<?= htmlspecialchars(BASE_URL) ?>/prodi<?= $isEditing ? '/' . (int) $prodi['id'] : '' ?>" method="POST">
  <div class="mb-3">
    <label for="kode" class="form-label">Kode</label>
    <input type="text" class="form-control" id="kode" name="kode" maxlength="10" value="<?= htmlspecialchars($prodi['kode'] ?? '') ?>" required>
  </div>
  <div class="mb-3">
    <label for="nama" class="form-label">Nama Program Studi</label>
    <input type="text" class="form-control" id="nama" name="nama" maxlength="100" value="<?= htmlspecialchars($prodi['nama'] ?? '') ?>" required>
  </div>
  <button type="submit" class="btn btn-primary">Simpan</button>
  <a href="<?= htmlspecialchars(BASE_URL) ?>/prodi" class="btn btn-secondary">Batal</a>
</form>
