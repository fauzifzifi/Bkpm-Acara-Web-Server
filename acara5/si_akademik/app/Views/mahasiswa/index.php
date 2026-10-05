<h1 class="mb-4">Daftar Mahasiswa</h1>
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>NIM</th>
            <th>Nama</th>
            <th>Prodi</th>
            <th>Angkatan</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($daftarMahasiswa as $mhs): ?>
            <tr>
                <td><?= $mhs->getNim() ?></td>
                <td><?= $mhs->getNama() ?></td>
                <td><?= $mhs->getProdi() ?></td>
                <td><?= $mhs->getAngkatan() ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>