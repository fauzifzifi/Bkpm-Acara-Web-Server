<?php
$val = function (string $key, $default = '') use ($mk) {
    return old($key, $mk[$key] ?? $default);
};
?>
<form action="<?= $action ?>" method="POST">
    <div class="mb-3">
        <label class="form-label">Kode</label>
        <input type="text" name="kode" class="form-control" maxlength="10" value="<?= e($val('kode')) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Nama Mata Kuliah</label>
        <input type="text" name="nama" class="form-control" maxlength="150" value="<?= e($val('nama')) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">SKS</label>
        <input type="number" name="sks" class="form-control" min="1" max="6" value="<?= e($val('sks', 3)) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Program Studi</label>
        <select name="prodi_id" class="form-select" required>
            <option value="">-- Pilih Program Studi --</option>
            <?php foreach ($daftarProdi as $p): ?>
                <option value="<?= $p['id'] ?>" <?= (string) $val('prodi_id') === (string) $p['id'] ? 'selected' : '' ?>>
                    <?= e($p['nama']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary"><?= $submit ?></button>
    <a href="<?= url('/matakuliah') ?>" class="btn btn-secondary">Batal</a>
</form>
