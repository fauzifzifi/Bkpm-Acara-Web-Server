<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SI Akademik</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= htmlspecialchars(BASE_URL) ?>/assets/css/app.css" rel="stylesheet">
</head>
<body>
  <?php include __DIR__ . '/../partials/header.php'; ?>
  <?php include __DIR__ . '/../partials/navbar.php'; ?>
  <?php include __DIR__ . '/../partials/flash.php'; ?>

  <main class="container mt-4">
    <?php require $content; ?>
  </main>

  <?php include __DIR__ . '/../partials/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
