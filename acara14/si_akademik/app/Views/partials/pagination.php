<?php if ($pager['pages'] > 1): ?>
    <nav aria-label="Pagination">
        <ul class="pagination">
            <li class="page-item <?= $pager['page'] <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= e(page_url($pager['page'] - 1)) ?>">&laquo;</a>
            </li>
            <?php for ($i = 1; $i <= $pager['pages']; $i++): ?>
                <li class="page-item <?= $i === $pager['page'] ? 'active' : '' ?>">
                    <a class="page-link" href="<?= e(page_url($i)) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= $pager['page'] >= $pager['pages'] ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= e(page_url($pager['page'] + 1)) ?>">&raquo;</a>
            </li>
        </ul>
    </nav>
<?php endif; ?>
