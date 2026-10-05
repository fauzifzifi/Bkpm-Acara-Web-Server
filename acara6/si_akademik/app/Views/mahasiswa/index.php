<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">Daftar Mahasiswa</h1>
    <a href="<?= url('/mahasiswa/create') ?>" class="btn btn-success">+ Tambah Mahasiswa</a>
</div>
<table class="table table-bordered table-striped bg-white">
    <thead class="table-dark">
        <tr><th>NIM</th><th>Nama</th><th>Prodi</th><th>Angkatan</th></tr>
    </thead>
    <tbody>
        <?php foreach ($daftarMahasiswa as $mhs): ?>
            <tr>
                <td><?= e($mhs->getNim()) ?></td>
                <td><?= e($mhs->getNama()) ?></td>
                <td><?= e($mhs->getProdi()) ?></td>
                <td><?= e($mhs->getAngkatan()) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<a href="<?= url('/mahasiswa/edit') ?>" class="btn btn-warning btn-sm">Halaman Edit</a>
