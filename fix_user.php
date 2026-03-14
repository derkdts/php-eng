<?php
/**
 * One-time utility script to update credentials
 */
$pdo = require __DIR__ . '/db.php';

$username = 'alime';
$password = password_hash('alime', PASSWORD_DEFAULT);

// Check if user exists, update if yes, insert if no
$stmt = $pdo->query("SELECT id FROM users LIMIT 1");
$user = $stmt->fetch();

if ($user) {
    $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
    $stmt->execute([$username, $password, $user['id']]);
    echo "Пользователь обновлен: $username / $password (hashed)";
} else {
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $password]);
    echo "Пользователь создан: $username";
}

// Optional: delete this file after use
// unlink(__FILE__);
?>
