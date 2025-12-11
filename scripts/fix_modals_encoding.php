<?php
/**
 * Fix encoding issues in modals.blade.php
 */

$file = __DIR__ . '/../resources/views/partials/super_admin/modals.blade.php';
$content = file_get_contents($file);

// Replace corrupted UTF-8 em-dash with regular hyphen
$content = str_replace("\xC3\xA2\xE2\x82\xAC\xE2\x80\x9C", "-", $content);

file_put_contents($file, $content);
echo "Fixed encoding issues in modals.blade.php\n";
