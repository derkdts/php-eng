<?php
$pdo = require __DIR__ . '/../db.php';
$stmt = $pdo->query("SELECT * FROM grammar ORDER BY created_at DESC");
$rules = $stmt->fetchAll();
?>

<div style="display: grid; gap: 2rem;">
    <!-- Add Grammar Section -->
    <div class="card">
        <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem;">New Grammar Rule</h3>
        <form action="index.php" method="POST" enctype="multipart/form-data" style="display: grid; gap: 1.5rem;">
            <input type="hidden" name="action" value="add_grammar">
            
            <div style="display: grid; gap: 8px;">
                <label class="input-label">Topic Title</label>
                <input type="text" name="title" required placeholder="Ex: Future Perfect Continuous" class="glass-input">
            </div>
            
            
            <div style="display: grid; gap: 8px;">
                <label class="input-label">Attach Visual Aid (Optional)</label>
                <input type="file" name="image" id="grammar-image-input" accept="image/*" style="color: var(--text-secondary); font-size: 0.85rem;">
                <div id="image-preview-container" style="margin-top: 1rem; display: none; border-radius: 12px; overflow: hidden; border: 1px solid var(--glass-border); position: relative;">
                    <img id="image-preview" src="" alt="Preview" style="width: 100%; display: block;">
                    <button type="button" id="remove-preview" class="btn-icon" style="position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.5); width: 24px; height: 24px;">
                        <span class="icon icon-trash" style="width: 14px; height: 14px;"></span>
                    </button>
                </div>
            </div>
            
            <button type="submit" class="primary-btn" style="justify-self: start;">Save Rule</button>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('grammar-image-input');
        const previewContainer = document.getElementById('image-preview-container');
        const previewImage = document.getElementById('image-preview');
        const removeButton = document.getElementById('remove-preview');

        function updatePreview(file) {
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
            }
        }

        fileInput.addEventListener('change', function(e) {
            updatePreview(e.target.files[0]);
        });

        removeButton.addEventListener('click', function() {
            fileInput.value = '';
            previewContainer.style.display = 'none';
            previewImage.src = '';
        });

        // Paste functionality
        window.addEventListener('paste', function(e) {
            const items = (e.clipboardData || e.originalEvent.clipboardData).items;
            for (let index in items) {
                const item = items[index];
                if (item.kind === 'file' && item.type.indexOf('image/') !== -1) {
                    const blob = item.getAsFile();
                    const file = new File([blob], "pasted-image-" + Date.now() + ".png", { type: blob.type });
                    
                    // Create a DataTransfer object to set the file input
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;
                    
                    updatePreview(file);
                    break;
                }
            }
        });
    });
    </script>

    <!-- Rules List -->
    <div style="display: grid; gap: 1rem;">
        <?php if (empty($rules)): ?>
            <div class="card" style="text-align: center; padding: 4rem; opacity: 0.5;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">⚖️</div>
                <p>No grammar notes yet. Add your first rule above.</p>
            </div>
        <?php else: ?>
            <?php foreach ($rules as $item): ?>
                <details class="card" style="padding: 0; overflow: hidden; cursor: pointer;">
                    <summary style="padding: 1.5rem; list-style: none; display: flex; justify-content: space-between; align-items: center; font-weight: 600; color: var(--accent-color);">
                        <span style="display: flex; align-items: center; gap: 12px;">
                            <span class="icon icon-scale"></span>
                            <?= h($item['title']) ?>
                            <button onclick="event.preventDefault(); speakText('<?= addslashes(h($item['title'])) ?>')" 
                                    class="btn-icon" style="margin-left: 8px; width: 28px; height: 28px;" title="Listen">
                                <span class="icon icon-speaker" style="width: 16px; height: 16px;"></span>
                            </button>
                        </span>
                        <span style="font-size: 0.8rem; opacity: 0.5;">EXPAND ▼</span>
                    </summary>
                    
                    <div style="padding: 2rem; border-top: 1px solid var(--glass-border); background: rgba(0,0,0,0.1);">
                        <?php if ($item['image_path']): ?>
                            <div style="margin-bottom: 2rem; border-radius: 20px; overflow: hidden; border: 1px solid var(--glass-border); box-shadow: 0 10px 30px rgba(0,0,0,0.4);">
                                <img src="<?= h($item['image_path']) ?>" alt="Visual" style="width: 100%; display: block;">
                            </div>
                        <?php endif; ?>
                        
                        <p style="white-space: pre-wrap; line-height: 1.8; color: var(--text-primary); font-size: 1.05rem;"><?= h($item['content']) ?></p>
                        
                        <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                            <form action="index.php" method="POST">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="delete_id" value="<?= $item['id'] ?>">
                                <input type="hidden" name="table" value="grammar">
                                <button type="submit" class="btn-icon delete" style="width: auto; padding: 0 1rem; gap: 8px;">
                                    <span class="icon icon-trash"></span>
                                    <span style="font-size: 0.8rem; font-weight: 600;">Delete Note</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </details>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
