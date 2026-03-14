<?php
$db_file = __DIR__ . '/database.db';

try {
    $pdo = new PDO("sqlite:" . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // --- AUTOMATIC MIGRATIONS ---

    // 1. Categories Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS categories (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL UNIQUE,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // 2. Vocabulary Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS vocabulary (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        word TEXT NOT NULL,
        translation TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Add category_id to vocabulary if missing
    $stmt = $pdo->query("PRAGMA table_info(vocabulary)");
    $cols = array_column($stmt->fetchAll(), 'name');
    if (!in_array('category_id', $cols)) {
        $pdo->exec("ALTER TABLE vocabulary ADD COLUMN category_id INTEGER REFERENCES categories(id) ON DELETE SET NULL");
    }

    // 3. Grammar Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS grammar (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        content TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Add image_path to grammar if missing
    $stmt = $pdo->query("PRAGMA table_info(grammar)");
    $cols = array_column($stmt->fetchAll(), 'name');
    if (!in_array('image_path', $cols)) {
        $pdo->exec("ALTER TABLE grammar ADD COLUMN image_path TEXT");
    }

    // 4. Users Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Create default admin if no users exist
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    if ($stmt->fetchColumn() == 0) {
        $username = 'alime_user_5821';
        $password = password_hash('r8!N$2xP#L9q', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $password]);
    }

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

return $pdo;
