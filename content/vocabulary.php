<?php
$pdo = require __DIR__ . '/../db.php';

$cat_stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $cat_stmt->fetchAll();

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

<div style="display: grid; gap: 2rem;">
    <!-- Add Section -->
    <div class="card">
        <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem;">New Vocabulary Entry</h3>
        <form action="index.php" method="POST" style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 1rem; align-items: end;">
            <input type="hidden" name="action" value="add_word">
            
            <div style="display: grid; gap: 8px;">
                <label style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600;">English</label>
                <input type="text" name="word" required placeholder="Ex: Serendipity" class="glass-input">
            </div>
            
            <div style="display: grid; gap: 8px;">
                <label style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600;">Russian</label>
                <input type="text" name="translation" required placeholder="Перевод" class="glass-input">
            </div>
            
            <div style="display: grid; gap: 8px;">
                <label style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600;">Category</label>
                <select name="category_id" class="glass-input">
                    <option value="">No category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $selected_category === (int)$cat['id'] ? 'selected' : '' ?>>
                            <?= h($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="primary-btn">Save Word</button>
        </form>
    </div>

    <!-- Filter & Grid -->
    <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h4 style="color: var(--text-secondary); font-weight: 500;">
                All Items (<?= count($words) ?>)
            </h4>
            
            <select class="glass-input" style="width: auto; padding: 0.5rem 1rem;" 
                    onchange="window.location.href = '?page=vocabulary' + (this.value ? '&category=' + this.value : '')">
                <option value="">Filtered by: All</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $selected_category === (int)$cat['id'] ? 'selected' : '' ?>>
                        By: <?= h($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
            <?php if (empty($words)): ?>
                <div class="card" style="grid-column: 1/-1; text-align: center; padding: 4rem; opacity: 0.5;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">🕳️</div>
                    <p>No words found in this section yet.</p>
                </div>
            <?php else: ?>
                <?php foreach ($words as $item): ?>
                    <div class="card word-card">
                        <?php if ($item['category_name']): ?>
                            <span class="category-tag"><?= h($item['category_name']) ?></span>
                        <?php endif; ?>
                        
                        <h4 class="word-text"><?= h($item['word']) ?></h4>
                        <p class="translation-text"><?= h($item['translation']) ?></p>
                        
                        <div class="action-group">
                            <button onclick="speakText('<?= addslashes(h($item['word'])) ?>')" 
                                    class="btn-icon" title="Listen">
                                <span class="icon icon-speaker"></span>
                            </button>
                            <button onclick="openEditModal(<?= $item['id'] ?>, '<?= h($item['word']) ?>', '<?= h($item['translation']) ?>', '<?= $item['category_id'] ?>')" 
                                    class="btn-icon" title="Edit">
                                <span class="icon icon-edit"></span>
                            </button>
                            <form action="index.php" method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="delete_id" value="<?= $item['id'] ?>">
                                <input type="hidden" name="table" value="vocabulary">
                                <button type="submit" class="btn-icon delete" title="Delete">
                                    <span class="icon icon-trash"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Edit Modal Area -->
<div id="editModal" class="modal-overlay" onclick="if(event.target==this) closeEditModal()">
    <div class="modal-container">
        <div class="card" style="background: var(--sidebar-bg);">
            <h3 style="margin-bottom: 2rem; font-size: 1.5rem;">Edit Word</h3>
            <form action="index.php" method="POST" style="display: grid; gap: 1.5rem;">
                <input type="hidden" name="action" value="edit_word">
                <input type="hidden" name="word_id" id="edit_id">
                
                <div style="display: grid; gap: 8px;">
                    <label class="input-label">English Word</label>
                    <input type="text" name="word" id="edit_word_input" required class="glass-input">
                </div>
                
                <div style="display: grid; gap: 8px;">
                    <label class="input-label">Russian Translation</label>
                    <input type="text" name="translation" id="edit_translation_input" required class="glass-input">
                </div>
                
                <div style="display: grid; gap: 8px;">
                    <label class="input-label">Select Category</label>
                    <select name="category_id" id="edit_category_id" class="glass-input">
                        <option value="">General</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= h($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <button type="submit" class="primary-btn" style="flex: 1;">Update Entry</button>
                    <button type="button" onclick="closeEditModal()" class="btn-icon" style="width: auto; padding: 0 1.5rem; height: auto;">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(id, word, translation, categoryId) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_word_input').value = word;
    document.getElementById('edit_translation_input').value = translation;
    document.getElementById('edit_category_id').value = categoryId;
    document.getElementById('editModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>
