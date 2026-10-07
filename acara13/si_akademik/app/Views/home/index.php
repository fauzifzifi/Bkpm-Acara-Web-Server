<div class="p-5 mb-4 bg-white rounded-3 border text-center">
    <h1 class="display-6">Selamat datang di SI Akademik</h1>
    <p class="lead">Sistem Informasi Akademik - Workshop Web Server.</p>
    <?php if (empty($_SESSION['logged_in'])): ?>
        <a href="<?= url('/login') ?>" class="btn btn-primary">Login</a>
    <?php else: ?>
        <a href="<?= url('/dashboard') ?>" class="btn btn-primary">Ke Dashboard</a>
    <?php endif; ?>
</div>
