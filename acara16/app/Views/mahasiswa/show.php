<h1 class="mb-4">Detail Mahasiswa</h1>

<table class="table table-bordered w-auto">
  <tr>
    <th>NIM</th>
    <td><?= htmlspecialchars($mhs['nim']) ?></td>
  </tr>
  <tr>
    <th>Nama</th>
    <td><?= htmlspecialchars($mhs['nama']) ?></td>
  </tr>
  <tr>
    <th>Email</th>
    <td><?= htmlspecialchars($mhs['email']) ?></td>
  </tr>
  <tr>
    <th>Prodi</th>
    <td><?= htmlspecialchars($mhs['prodi'] ?? '') ?></td>
  </tr>
  <tr>
    <th>Angkatan</th>
    <td><?= htmlspecialchars((string) $mhs['angkatan']) ?></td>
  </tr>
  <tr>
    <th>Status</th>
    <td><?= htmlspecialchars(ucfirst($mhs['status'])) ?></td>
  </tr>
</table>

<a href="<?= htmlspecialchars(BASE_URL) ?>/mahasiswa" class="btn btn-secondary">
  &larr; Kembali ke Daftar
</a>
