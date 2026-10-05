<?php
$val = function (string $key, $default = '') use ($prodi) {
    return old($key, $prodi[$key] ?? $default);
};
?>
<form action="<?= $action ?>" method="POST">
    <div class="mb-3">
        <label class="form-label">Kode</label>
        <input type="text" name="kode" class="form-control" maxlength="10" value="<?= e($val('kode')) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Nama Program Studi</label>
        <input type="text" name="nama" class="form-control" maxlength="100" value="<?= e($val('nama')) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary"><?= $submit ?></button>
    <a href="<?= url('/prodi') ?>" class="btn btn-secondary">Batal</a>
</form>
