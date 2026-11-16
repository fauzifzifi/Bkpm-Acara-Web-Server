<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= htmlspecialchars(BASE_URL) ?>/">SI Akademik</a>
    <div class="navbar-nav ms-auto">
      <a class="nav-link text-white" href="<?= htmlspecialchars(BASE_URL) ?>/mahasiswa">Daftar Mahasiswa</a>
      <a class="nav-link text-white" href="<?= htmlspecialchars(BASE_URL) ?>/mahasiswa/create">Tambah Mahasiswa</a>
      <a class="nav-link text-white" href="<?= htmlspecialchars(BASE_URL) ?>/prodi">Prodi</a>
      <a class="nav-link text-white" href="<?= htmlspecialchars(BASE_URL) ?>/matakuliah">Mata Kuliah</a>
      <?php if (!empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
        <a class="nav-link text-white" href="<?= htmlspecialchars(BASE_URL) ?>/dashboard">Dashboard</a>
        <span class="nav-link text-white-50">Halo, <?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></span>
        <a class="nav-link text-white" href="<?= htmlspecialchars(BASE_URL) ?>/logout">Logout</a>
      <?php else: ?>
        <a class="nav-link text-white" href="<?= htmlspecialchars(BASE_URL) ?>/login">Login</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
