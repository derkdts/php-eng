<?php
/**
 * Utility functions for English Prep
 */

/**
 * Clean and escape output
 */
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Handle Image Upload for Grammar
 */
function handle_grammar_upload($file) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $upload_dir = __DIR__ . '/../uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $file_name = uniqid('img_', true) . '.' . $file_ext;
    $target_path = $upload_dir . $file_name;

    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        return 'uploads/' . $file_name;
    }

    return null;
}

/**
 * Delete Image file
 */
function delete_image_file($path) {
    $full_path = __DIR__ . '/../' . $path;
    if ($path && file_exists($full_path) && is_file($full_path)) {
        return unlink($full_path);
    }
    return false;
}
