<!-- // Includes/functions.php -->
<?php
function getTagClass($tag) {
    $tagClasses = [
        'New to Game' => 'badge-new-to-game',
        'Advice' => 'badge-advice',
        'Help' => 'badge-help',
        'Rules' => 'badge-rules',
        'Strategy' => 'badge-strategy'
    ];

    return $tagClasses[trim($tag)] ?? 'badge-default';
}
?>