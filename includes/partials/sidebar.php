<aside class="sidebar">
    <div class="logo">
        <h1 style="display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 1.8rem;">🇬🇧</span>
            <span>English Prep</span>
        </h1>
    </div>
    
    <nav class="nav-menu">
        <a href="?page=vocabulary" class="nav-item <?= $page === 'vocabulary' ? 'active' : '' ?>">
            <span class="icon icon-book"></span>
            <span class="nav-text">Vocabulary</span>
        </a>
        <a href="?page=categories" class="nav-item <?= $page === 'categories' ? 'active' : '' ?>">
            <span class="icon icon-tag"></span>
            <span class="nav-text">Categories</span>
        </a>
        <a href="?page=grammar" class="nav-item <?= $page === 'grammar' ? 'active' : '' ?>">
            <span class="icon icon-scale"></span>
            <span class="nav-text">Grammar</span>
        </a>
    </nav>
    
    <?php if (isset($_SESSION['username'])): ?>
    <div class="sidebar-footer">
        <div class="user-info">
            <span class="user-avatar"><?= mb_substr($_SESSION['username'], 0, 1, 'UTF-8') ?></span>
            <span class="username"><?= h($_SESSION['username']) ?></span>
        </div>
        <a href="?page=logout" class="nav-item logout-btn">
            <span class="icon icon-logout"></span>
            <span class="nav-text">Выйти</span>
        </a>
    </div>
    <?php endif; ?>
    
    <div style="padding: 1rem; text-align: center; color: var(--text-secondary); font-size: 0.75rem;">
        <p>&copy; 2026</p>
    </div>
</aside>

<style>
.sidebar-footer {
    margin-top: auto;
    padding: 1rem;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
}

.user-info {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0 12px;
    margin-bottom: 8px;
}

.user-avatar {
    width: 32px;
    height: 32px;
    background: var(--primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: white;
    text-transform: uppercase;
    font-size: 0.85rem;
}

.username {
    font-size: 0.9rem;
    color: var(--text-primary);
    font-weight: 500;
}

.logout-btn {
    color: #fca5a5 !important;
}

.logout-btn:hover {
    background: rgba(239, 68, 68, 0.1) !important;
}
</style>
