<!-- // Includes/functions.php -->
<?php
function getTagClass($tag) {
    $tagClasses = [
        'New to Game' => 'badge-new-to-game',
        'Advice' => 'badge-advice',
        'Help' => 'badge-help',
        // Add more mappings as needed
    ];

    return $tagClasses[trim($tag)] ?? 'badge-default';
}
?>