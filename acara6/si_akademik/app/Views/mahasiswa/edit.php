<h1 class="mb-4">Edit Mahasiswa</h1>
<form action="<?= url('/mahasiswa') ?>" method="POST">
    <div class="mb-3">
        <label class="form-label">NIM</label>
        <input type="text" name="nim" class="form-control" value="24010001" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control" value="Budi Santoso" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Program Studi</label>
        <input type="text" name="prodi" class="form-control" value="Teknik Informatika" required>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="<?= url('/mahasiswa') ?>" class="btn btn-secondary">Batal</a>
</form>
