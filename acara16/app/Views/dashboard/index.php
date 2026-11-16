<h1 class="mb-4">Dashboard</h1>

<p>
  Selamat datang, <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'Pengguna') ?></strong>.
  Halaman ini hanya bisa diakses setelah login karena dilindungi <code>AuthMiddleware</code>.
</p>

<a href="<?= htmlspecialchars(BASE_URL) ?>/mahasiswa" class="btn btn-primary">
  Kelola Data Mahasiswa
</a>
