<?php
// Tampilkan flash message satu kali, lalu hapus dari session.
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<?php if ($flash): ?>
    <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show" role="alert">
        <?= e($flash['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
