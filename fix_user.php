<?php
/**
 * One-time utility script to update credentials
 */
$pdo = require __DIR__ . '/db.php';

$username = 'admin';
$password = password_hash('admin', PASSWORD_DEFAULT);

// Check if user exists, update if yes, insert if no
$stmt = $pdo->query("SELECT id FROM users LIMIT 1");
$user = $stmt->fetch();

if ($user) {
    $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
    $stmt->execute([$username, $password, $user['id']]);
    echo "<h1>База обновлена!</h1>";
    echo "<p><b>Логин:</b> $username</p>";
    echo "<p><b>Пароль:</b> r8!N$2xP#L9q</p>";
} else {
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $password]);
    echo "<h1>Пользователь создан!</h1>";
    echo "<p><b>Логин:</b> $username</p>";
    echo "<p><b>Пароль:</b> r8!N$2xP#L9q</p>";
}

// Optional: delete this file after use
// unlink(__FILE__);
?>