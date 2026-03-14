<?php
$pdo = require __DIR__ . '/../db.php';
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $stmt->fetchAll();
?>

<div style="display: grid; gap: 2rem;">
    <!-- Add Category -->
    <div class="card">
        <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Create New Label</h3>
        <form action="index.php" method="POST" style="display: flex; gap: 1rem; align-items: end;">
            <input type="hidden" name="action" value="add_category">
            <div style="flex: 1; max-width: 400px; display: grid; gap: 8px;">
                <label class="input-label">Label Name</label>
                <input type="text" name="category_name" required placeholder="Ex: Travel, Work..." class="glass-input">
            </div>
            <button type="submit" class="primary-btn">Create Label</button>
        </form>
    </div>

    <!-- Category Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.5rem;">
        <?php if (empty($categories)): ?>
            <div class="card" style="grid-column: 1/-1; text-align: center; padding: 4rem; opacity: 0.5;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🏷️</div>
                <p>Workspace is empty. Create your first category.</p>
            </div>
        <?php else: ?>
            <?php foreach ($categories as $cat): ?>
                <div class="card" style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 2rem;">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <span class="icon icon-tag" style="color: var(--accent-color);"></span>
                        <span style="font-weight: 600; font-size: 1.1rem;"><?= h($cat['name']) ?></span>
                    </div>
                    
                    <form action="index.php" method="POST" style="margin: 0;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="delete_id" value="<?= $cat['id'] ?>">
                        <input type="hidden" name="table" value="categories">
                        <button type="submit" class="btn-icon delete" title="Remove Label">
                            <span class="icon icon-trash"></span>
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
