<?php
$pdo = require 'db.php';
$stmt = $pdo->query("SELECT * FROM grammar ORDER BY created_at DESC");
$rules = $stmt->fetchAll();
?>

<div class="card">
    <h3 class="section-title">Грамматика</h3>
    
    <!-- Форма добавления правила -->
    <div class="form-container card" style="background: rgba(255, 255, 255, 0.02); margin-bottom: 2rem; padding: 1.5rem;">
        <h4 style="margin-bottom: 1rem; font-weight: 400; color: var(--text-secondary);">Добавить новое правило</h4>
        <form action="index.php" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1rem;">
            <div>
                <label for="title" style="display: block; font-size: 0.8rem; margin-bottom: 0.5rem; color: var(--text-secondary);">Название темы</label>
                <input type="text" id="title" name="title" required placeholder="Напр: Present Perfect"
                       style="width: 100%; padding: 0.8rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--glass-border); border-radius: 8px; color: #fff; outline: none; font-family: inherit;">
            </div>
            <div>
                <label for="content" style="display: block; font-size: 0.8rem; margin-bottom: 0.5rem; color: var(--text-secondary);">Правило / Заметка</label>
                <textarea id="content" name="content" required rows="4" placeholder="Опишите правило здесь..."
                          style="width: 100%; padding: 0.8rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--glass-border); border-radius: 8px; color: #fff; outline: none; font-family: inherit; resize: vertical;"></textarea>
            </div>
            <div>
                <label for="image" style="display: block; font-size: 0.8rem; margin-bottom: 0.5rem; color: var(--text-secondary);">Фотография (необязательно)</label>
                <input type="file" id="image" name="image" accept="image/*"
                       style="width: 100%; padding: 0.5rem; color: var(--text-secondary); font-size: 0.9rem;">
            </div>
            <button type="submit" 
                    style="align-self: flex-start; padding: 0.8rem 2.5rem; background: var(--active-gradient); border: none; border-radius: 8px; color: #fff; font-weight: 600; cursor: pointer; transition: transform 0.2s;">
                Сохранить правило
            </button>
        </form>
    </div>

    <div style="margin-top: 2rem;">
        <?php if (empty($rules)): ?>
            <p class="placeholder-text">Заметок по грамматике пока нет. Добавьте ваше первое правило!</p>
        <?php else: ?>
            <?php foreach ($rules as $item): ?>
                <details class="card grammar-card" style="padding: 1.2rem; background: rgba(255,255,255,0.03); cursor: pointer; margin-bottom: 1rem; border: 1px solid var(--glass-border); position: relative; transition: all 0.3s ease;">
                    <summary style="font-weight: 600; color: var(--accent-color); outline: none; list-style: none; display: flex; justify-content: space-between; align-items: center; padding-right: 2rem;">
                        <span><?= htmlspecialchars($item['title']) ?></span>
                        <span style="font-size: 0.8rem; opacity: 0.5;">▼</span>
                    </summary>
                    
                    <div style="margin-top: 1.5rem; border-top: 1px solid var(--glass-border); padding-top: 1rem;">
                        <?php if ($item['image_path']): ?>
                            <div style="margin-bottom: 1.5rem; border-radius: 12px; overflow: hidden; border: 1px solid var(--glass-border);">
                                <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="Grammar Visual" style="max-width: 100%; display: block;">
                            </div>
                        <?php endif; ?>
                        
                        <p style="font-size: 1rem; color: var(--text-secondary); line-height: 1.7; white-space: pre-wrap;"><?= htmlspecialchars($item['content']) ?></p>
                        
                        <form action="index.php" method="POST" style="margin-top: 1.5rem; display: flex; justify-content: flex-end;">
                            <input type="hidden" name="delete_id" value="<?= $item['id'] ?>">
                            <input type="hidden" name="table" value="grammar">
                            <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 0.9rem; display: flex; align-items: center; gap: 4px;" title="Удалить">
                                🗑️ <span style="font-size: 0.8rem;">Удалить правило</span>
                            </button>
                        </form>
                    </div>
                </details>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
