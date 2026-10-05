<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>500 - Terjadi kesalahan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container text-center mt-5">
        <h1 class="display-4">500</h1>
        <p class="lead">Terjadi kesalahan pada server.</p>
        <?php if (!empty($debug)): ?>
            <div class="alert alert-danger text-start"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <a href="<?= url('/') ?>" class="btn btn-primary">Kembali ke Beranda</a>
    </div>
</body>
</html>
