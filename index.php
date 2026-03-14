<?php
$pdo = require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['word']) && isset($_POST['translation'])) {
    $word = trim($_POST['word']);
    $translation = trim($_POST['translation']);
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    
    if (!empty($word) && !empty($translation)) {
        $stmt = $pdo->prepare("INSERT INTO vocabulary (word, translation, category_id) VALUES (?, ?, ?)");
        $stmt->execute([$word, $translation, $category_id]);
    }
    
    header('Location: ?page=vocabulary' . ($category_id ? '&category=' . $category_id : ''));
    exit;
}

// Handle Category Addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name'])) {
    $name = trim($_POST['category_name']);
    
    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT OR IGNORE INTO categories (name) VALUES (?)");
        $stmt->execute([$name]);
    }
    
    header('Location: ?page=categories');
    exit;
}

// Handle Delete Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    $table = isset($_POST['table']) && $_POST['table'] === 'grammar' ? 'grammar' : 'vocabulary';
    
    // If deleting from grammar, check for image
    if ($table === 'grammar') {
        $stmt = $pdo->prepare("SELECT image_path FROM grammar WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if ($row && $row['image_path'] && file_exists($row['image_path'])) {
            unlink($row['image_path']);
        }
    }
    
    $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ?");
    $stmt->execute([$id]);
    
    header('Location: ?page=' . ($table === 'grammar' ? 'grammar' : 'vocabulary'));
    exit;
}

// Handle Grammar Addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title']) && isset($_POST['content'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $image_path = null;
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '.' . $file_ext;
        $target_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image_path = $target_path;
        }
    }
    
    if (!empty($title) && !empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO grammar (title, content, image_path) VALUES (?, ?, ?)");
        $stmt->execute([$title, $content, $image_path]);
    }
    
    header('Location: ?page=grammar');
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'vocabulary';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>English Learning Notes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="app-container">
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
        
        <main class="content-area">
            <header class="top-header">
                <?php
                $page_title = 'Грамматика';
                if ($page === 'vocabulary') $page_title = 'Словарный запас';
                if ($page === 'categories') $page_title = 'Категории';
                ?>
                <h2><?= $page_title ?></h2>
                <div class="user-profile">
                    <div class="avatar">A</div>
                </div>
            </header>
            
            <section class="main-content">
                <?php
                if ($page === 'vocabulary') {
                    include 'content/vocabulary.php';
                } elseif ($page === 'categories') {
                    include 'content/categories.php';
                } else {
                    include 'content/grammar.php';
                }
                ?>
            </section>
        </main>
    </div>
</body>
</html>
