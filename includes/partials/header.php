<header class="top-header">
    <div>
        <h2 class="page-title"><?= $display_title ?></h2>
        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 4px;">Welcome back</p>
    </div>

    <div style="display: flex; align-items: center; gap: 20px;">
        <div
            style="width: 48px; height: 48px; border-radius: 16px; background: var(--active-gradient); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem; box-shadow: 0 8px 16px var(--accent-glow);">
            <?= isset($_SESSION['username']) ? mb_substr($_SESSION['username'], 0, 1, 'UTF-8') : 'A' ?>
        </div>
    </div>
</header>