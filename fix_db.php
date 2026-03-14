<?php
$pdo = require 'db.php';

try {
    // Check if column exists
    $stmt = $pdo->query("PRAGMA table_info(vocabulary)");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $column_names = array_column($columns, 'name');

    if (!in_array('category_id', $column_names)) {
        echo "Adding category_id to vocabulary table...\n";
        $pdo->exec("ALTER TABLE vocabulary ADD COLUMN category_id INTEGER REFERENCES categories(id) ON DELETE SET NULL");
        echo "Column added successfully.\n";
    } else {
        echo "Column category_id already exists.\n";
    }

    // Verify categories table
    $pdo->exec("CREATE TABLE IF NOT EXISTS categories (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL UNIQUE,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Categories table verified.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
