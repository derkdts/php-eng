<?php
$pdo = require __DIR__ . '/../db.php';
require __DIR__ . '/functions.php';

// Route Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Login Action
    if (isset($_POST['action']) && $_POST['action'] === 'login') {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        
        if ($username && $password) {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: ?page=vocabulary');
                exit;
            } else {
                $_SESSION['error'] = 'Неверное имя пользователя или пароль';
            }
        }
        header('Location: ?page=login');
        exit;
    }

    // Add Word
    if (isset($_POST['action']) && $_POST['action'] === 'add_word') {
        $word = trim($_POST['word'] ?? '');
        $translation = trim($_POST['translation'] ?? '');
        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        
        if ($word && $translation) {
            $stmt = $pdo->prepare("INSERT INTO vocabulary (word, translation, category_id) VALUES (?, ?, ?)");
            $stmt->execute([$word, $translation, $category_id]);
        }
        header('Location: ?page=vocabulary' . ($category_id ? '&category=' . $category_id : ''));
        exit;
    }

    // Edit Word
    if (isset($_POST['action']) && $_POST['action'] === 'edit_word') {
        $id = (int)($_POST['word_id'] ?? 0);
        $word = trim($_POST['word'] ?? '');
        $translation = trim($_POST['translation'] ?? '');
        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        
        if ($id && $word && $translation) {
            $stmt = $pdo->prepare("UPDATE vocabulary SET word = ?, translation = ?, category_id = ? WHERE id = ?");
            $stmt->execute([$word, $translation, $category_id, $id]);
        }
        header('Location: ?page=vocabulary' . ($category_id ? '&category=' . $category_id : ''));
        exit;
    }

    // Add Category
    if (isset($_POST['action']) && $_POST['action'] === 'add_category') {
        $name = trim($_POST['category_name'] ?? '');
        if ($name) {
            $stmt = $pdo->prepare("INSERT OR IGNORE INTO categories (name) VALUES (?)");
            $stmt->execute([$name]);
        }
        header('Location: ?page=categories');
        exit;
    }

    // Add Grammar
    if (isset($_POST['action']) && $_POST['action'] === 'add_grammar') {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $image_path = handle_grammar_upload($_FILES['image'] ?? null);
        
        if ($title) {
            $stmt = $pdo->prepare("INSERT INTO grammar (title, content, image_path) VALUES (?, ?, ?)");
            $stmt->execute([$title, $content, $image_path]);
        }
        header('Location: ?page=grammar');
        exit;
    }

    // Handle Delete (Universal)
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = (int)($_POST['delete_id'] ?? 0);
        $table = $_POST['table'] ?? '';
        
        if ($id && in_array($table, ['vocabulary', 'categories', 'grammar'])) {
            // Cleanup image if grammar
            if ($table === 'grammar') {
                $stmt = $pdo->prepare("SELECT image_path FROM grammar WHERE id = ?");
                $stmt->execute([$id]);
                $row = $stmt->fetch();
                if ($row && $row['image_path']) {
                    delete_image_file($row['image_path']);
                }
            }
            
            $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ?");
            $stmt->execute([$id]);
        }
        
        $redirect = $table;
        if ($table === 'categories') $redirect = 'categories';
        elseif ($table === 'grammar') $redirect = 'grammar';
        else $redirect = 'vocabulary';
        
        header("Location: ?page=$redirect");
        exit;
    }
}
