<?php
// Partial flash message: dipakai di layout utama agar tampilannya konsisten
// di semua halaman (login sukses, logout, error, dsb).
$flashTypes = [
    'flash_success' => 'success',
    'flash_info'    => 'info',
    'flash_error'   => 'danger',
];
?>
<?php foreach ($flashTypes as $key => $variant): ?>
  <?php if (!empty($_SESSION[$key])): ?>
    <div class="alert alert-<?= $variant ?> alert-dismissible fade show mb-0 rounded-0" role="alert">
      <?= htmlspecialchars($_SESSION[$key]) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION[$key]); ?>
  <?php endif; ?>
<?php endforeach; ?>
