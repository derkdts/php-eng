<?php
$pdo = require 'db.php';
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $stmt->fetchAll();
?>

<div class="card">
    <h3 class="section-title">Категории</h3>
    
    <div class="form-container card" style="background: rgba(255, 255, 255, 0.02); margin-bottom: 2rem; padding: 1.5rem;">
        <h4 style="margin-bottom: 1rem; font-weight: 400; color: var(--text-secondary);">Создать новую категорию</h4>
        <form action="index.php" method="POST" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 250px;">
                <label for="category_name" style="display: block; font-size: 0.8rem; margin-bottom: 0.5rem; color: var(--text-secondary);">Название категории</label>
                <input type="text" id="category_name" name="category_name" required placeholder="Напр: Природа, Путешествия..."
                       style="width: 100%; padding: 0.8rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--glass-border); border-radius: 8px; color: #fff; outline: none; font-family: inherit;">
            </div>
            <button type="submit" 
                    style="padding: 0.8rem 2rem; background: var(--active-gradient); border: none; border-radius: 8px; color: #fff; font-weight: 600; cursor: pointer;">
                Создать
            </button>
        </form>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
        <?php if (empty($categories)): ?>
            <p class="placeholder-text">У вас пока нет созданных категорий.</p>
        <?php else: ?>
            <?php foreach ($categories as $cat): ?>
                <div class="card" style="padding: 1.2rem; background: rgba(255,255,255,0.03); border: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: 500;"><?= htmlspecialchars($cat['name']) ?></span>
                    
                    <form action="index.php" method="POST" style="margin: 0;">
                        <input type="hidden" name="delete_id" value="<?= $cat['id'] ?>">
                        <input type="hidden" name="table" value="categories">
                        <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; opacity: 0.6; transition: opacity 0.2s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.6">
                            🗑️
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
