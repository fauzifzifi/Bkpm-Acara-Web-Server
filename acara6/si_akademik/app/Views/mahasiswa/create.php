<h1 class="mb-4">Tambah Mahasiswa</h1>
<form action="<?= url('/mahasiswa') ?>" method="POST">
    <div class="mb-3">
        <label class="form-label">NIM</label>
        <input type="text" name="nim" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Program Studi</label>
        <input type="text" name="prodi" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="<?= url('/mahasiswa') ?>" class="btn btn-secondary">Batal</a>
</form>
