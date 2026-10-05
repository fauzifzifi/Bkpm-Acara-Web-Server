<?php
// Form dipakai bersama oleh create & edit. $mhs = null saat tambah.
$val = function (string $key, $default = '') use ($mhs) {
    return old($key, $mhs[$key] ?? $default);
};
?>
<form action="<?= $action ?>" method="POST">
    <div class="mb-3">
        <label class="form-label">NIM</label>
        <input type="text" name="nim" class="form-control" value="<?= e($val('nim')) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control" value="<?= e($val('nama')) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= e($val('email')) ?>">
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
    <div class="mb-3">
        <label class="form-label">Angkatan</label>
        <input type="number" name="angkatan" class="form-control" value="<?= e($val('angkatan', date('Y'))) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            <?php foreach ($statusList as $s): ?>
                <option value="<?= $s ?>" <?= $val('status', 'aktif') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary"><?= $submit ?></button>
    <a href="<?= url('/mahasiswa') ?>" class="btn btn-secondary">Batal</a>
</form>
