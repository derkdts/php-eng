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
    
    <div style="margin-top: auto; padding: 1rem; text-align: center; color: var(--text-secondary); font-size: 0.75rem;">
        <p>&copy; 2026</p>
    </div>
</aside>
