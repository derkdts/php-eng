<aside class="sidebar">
    <div class="logo">
        <span class="icon">🇬🇧</span>
        <h1>English Prep</h1>
    </div>
    <nav class="nav-menu">
        <a href="?page=vocabulary" class="nav-item <?= $page === 'vocabulary' ? 'active' : '' ?>">
            <span class="nav-icon">📚</span>
            <span class="nav-text">Словарный запас</span>
        </a>
        <a href="?page=categories" class="nav-item <?= $page === 'categories' ? 'active' : '' ?>">
            <span class="nav-icon">🏷️</span>
            <span class="nav-text">Категории</span>
        </a>
        <a href="?page=grammar" class="nav-item <?= $page === 'grammar' ? 'active' : '' ?>">
            <span class="nav-icon">⚖️</span>
            <span class="nav-text">Грамматика</span>
        </a>
    </nav>
    <div class="sidebar-footer">
        <p>© 2026 English Prep</p>
    </div>
</aside>
