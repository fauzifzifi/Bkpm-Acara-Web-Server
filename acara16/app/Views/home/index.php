<h1 class="mb-3">Selamat Datang di SI Akademik</h1>
<p>
  Ini adalah halaman utama (<code>/</code>) yang diakses melalui
  <strong>routing</strong>. Sebelumnya URL berbentuk
  <code>index.php?...</code>, sekarang menjadi URL bersih seperti
  <code><?= htmlspecialchars(BASE_URL) ?>/mahasiswa</code>.
</p>
<a href="<?= htmlspecialchars(BASE_URL) ?>/mahasiswa" class="btn btn-primary">
  Lihat Daftar Mahasiswa
</a>

<?php if (!empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
  <a href="<?= htmlspecialchars(BASE_URL) ?>/dashboard" class="btn btn-outline-secondary">
    Ke Dashboard
  </a>
<?php else: ?>
  <a href="<?= htmlspecialchars(BASE_URL) ?>/login" class="btn btn-outline-secondary">
    Login
  </a>
  <p class="text-muted small mt-3 mb-0">
    Catatan: halaman <code>/mahasiswa</code> dan <code>/dashboard</code> kini dilindungi
    <code>AuthMiddleware</code> &mdash; jika belum login, Anda akan diarahkan ke halaman Login.
  </p>
<?php endif; ?>
