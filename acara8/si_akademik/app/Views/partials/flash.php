<?php
// Tampilkan flash message satu kali, lalu hapus dari session.
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<?php if ($flash): ?>
    <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show" role="alert">
        <?php if (is_array($flash['message'])): ?>
            <ul class="mb-0">
                <?php foreach ($flash['message'] as $msg): ?>
                    <li><?= e($msg) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <?= e($flash['message']) ?>
        <?php endif; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
