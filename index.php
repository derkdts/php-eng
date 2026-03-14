<?php
/**
 * Index.php - Main entry point
 * Refactored to use centralized logic in includes/
 */

require_once __DIR__ . '/includes/actions.php';

$page = $_GET['page'] ?? 'vocabulary';
$allowed_pages = ['vocabulary', 'categories', 'grammar'];
if (!in_array($page, $allowed_pages)) $page = 'vocabulary';

$titles = [
    'vocabulary' => 'Vocabulary',
    'categories' => 'Categories',
    'grammar' => 'Grammar'
];
$display_title = $titles[$page] ?? 'English Prep';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>English Prep - Learning Notes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="app-container">
        <?php include 'includes/partials/sidebar.php'; ?>
        
        <main class="content-area">
            <?php include 'includes/partials/header.php'; ?>
            
            <section class="main-content">
                <?php include "content/$page.php"; ?>
            </section>
        </main>
    </div>
    <script>
        function speakText(text, lang = 'en-US') {
            if (!window.speechSynthesis) return;
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = lang;
            utterance.rate = 0.9;
            window.speechSynthesis.speak(utterance);
        }
    </script>
</body>
</html>
