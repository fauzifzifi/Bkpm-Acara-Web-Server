<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= url('/') ?>">SI Akademik</a>
            <div class="d-flex align-items-center gap-2">
                <?php if (!empty($_SESSION['logged_in'])): ?>
                    <a href="<?= url('/dashboard') ?>" class="btn btn-outline-light btn-sm">Dashboard</a>
                    <a href="<?= url('/mahasiswa') ?>" class="btn btn-outline-light btn-sm">Mahasiswa</a>
                    <a href="<?= url('/prodi') ?>" class="btn btn-outline-light btn-sm">Prodi</a>
                    <a href="<?= url('/matakuliah') ?>" class="btn btn-outline-light btn-sm">Mata Kuliah</a>
                    <a href="<?= url('/logout') ?>" class="btn btn-outline-danger btn-sm">Logout</a>
                <?php else: ?>
                    <a href="<?= url('/login') ?>" class="btn btn-outline-light btn-sm">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
