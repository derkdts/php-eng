<?php
$pdo = require 'db.php';

// Get categories for the select list
$cat_stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $cat_stmt->fetchAll();

// Handle filtering
$selected_category = isset($_GET['category']) ? (int)$_GET['category'] : null;

if ($selected_category) {
    $stmt = $pdo->prepare("SELECT v.*, c.name as category_name 
                           FROM vocabulary v 
                           LEFT JOIN categories c ON v.category_id = c.id 
                           WHERE v.category_id = ? 
                           ORDER BY v.created_at DESC");
    $stmt->execute([$selected_category]);
} else {
    $stmt = $pdo->query("SELECT v.*, c.name as category_name 
                         FROM vocabulary v 
                         LEFT JOIN categories c ON v.category_id = c.id 
                         ORDER BY v.created_at DESC");
}
$words = $stmt->fetchAll();
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h3 class="section-title" style="margin: 0;">Словарный запас</h3>
        
        <!-- Фильтр по категориям -->
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 0.9rem; color: var(--text-secondary);">Фильтр:</span>
            <select onchange="window.location.href = '?page=vocabulary' + (this.value ? '&category=' + this.value : '')"
                    style="background: rgba(15, 23, 42, 0.8); border: 1px solid var(--glass-border); border-radius: 8px; color: #fff; padding: 0.5rem; outline: none; cursor: pointer;">
                <option value="">Все слова</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $selected_category === (int)$cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    
    <!-- Форма добавления слова -->
    <div class="form-container card" style="background: rgba(255, 255, 255, 0.02); margin-bottom: 2rem; padding: 1.5rem;">
        <h4 style="margin-bottom: 1rem; font-weight: 400; color: var(--text-secondary);">Добавить новое слово</h4>
        <form action="index.php" method="POST" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 150px;">
                <label for="word" style="display: block; font-size: 0.8rem; margin-bottom: 0.5rem; color: var(--text-secondary);">Слово (En)</label>
                <input type="text" id="word" name="word" required 
                       style="width: 100%; padding: 0.8rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--glass-border); border-radius: 8px; color: #fff; outline: none; font-family: inherit;">
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label for="translation" style="display: block; font-size: 0.8rem; margin-bottom: 0.5rem; color: var(--text-secondary);">Перевод (Ru)</label>
                <input type="text" id="translation" name="translation" required 
                       style="width: 100%; padding: 0.8rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--glass-border); border-radius: 8px; color: #fff; outline: none; font-family: inherit;">
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label for="category_id" style="display: block; font-size: 0.8rem; margin-bottom: 0.5rem; color: var(--text-secondary);">Категория</label>
                <select id="category_id" name="category_id" 
                        style="width: 100%; padding: 0.8rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--glass-border); border-radius: 8px; color: #fff; outline: none; font-family: inherit;">
                    <option value="">Без категории</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $selected_category === (int)$cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" 
                    style="padding: 0.8rem 2rem; background: var(--active-gradient); border: none; border-radius: 8px; color: #fff; font-weight: 600; cursor: pointer;">
                Добавить
            </button>
        </form>
    </div>

    <div style="margin-top: 1rem; display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.5rem;">
        <?php if (empty($words)): ?>
            <p class="placeholder-text">Список пуст. Попробуйте изменить фильтр или добавить слово.</p>
        <?php else: ?>
            <?php foreach ($words as $item): ?>
                <div class="card word-card" style="padding: 1.5rem; background: rgba(255,255,255,0.03); border: 1px solid var(--glass-border); position: relative; overflow: hidden; transition: all 0.3s ease;">
                    <?php if ($item['category_name']): ?>
                        <span style="font-size: 0.7rem; color: var(--accent-color); text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 0.5rem;">
                            <?= htmlspecialchars($item['category_name']) ?>
                        </span>
                    <?php endif; ?>
                    
                    <h4 style="color: #fff; margin-bottom: 0.5rem; font-size: 1.3rem;"><?= htmlspecialchars($item['word']) ?></h4>
                    <p style="font-size: 1rem; color: var(--text-secondary);"><?= htmlspecialchars($item['translation']) ?></p>
                    
                    <form action="index.php" method="POST" style="position: absolute; top: 10px; right: 10px; opacity: 0; transition: opacity 0.3s;" class="delete-form">
                        <input type="hidden" name="delete_id" value="<?= $item['id'] ?>">
                        <input type="hidden" name="table" value="vocabulary">
                        <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer;" title="Удалить">🗑️</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
