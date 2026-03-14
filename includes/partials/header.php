<header class="top-header">
    <?php
    $page_titles = [
        'vocabulary' => 'Словарный запас',
        'categories' => 'Категории',
        'grammar' => 'Грамматика'
    ];
    $display_title = $page_titles[$page] ?? 'English Prep';
    ?>
    <h2><?= $display_title ?></h2>
    <div class="user-profile">
        <div class="avatar">A</div>
    </div>
</header>
