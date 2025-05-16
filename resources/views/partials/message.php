<?php
/**
 * Flash message partial
 * Displays session flash messages with appropriate styling
 */

// Define all supported message types with their corresponding Bootstrap classes
$messageTypes = [
    'error' => 'danger',
    'success' => 'success',
    'warning' => 'warning',
    'info' => 'info'
];

// Loop through all message types
foreach ($messageTypes as $type => $alertClass):
    // Get flash message for this type
    $flashMessage = session()->getFlash($type);
    
    // Display message if it exists
    if ($flashMessage):
        // Handle both string and array messages
        if (is_array($flashMessage)):
?>
        <div class="alert alert-<?= $alertClass ?> alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                <?php foreach ($flashMessage as $message): ?>
                    <li><?= htmlspecialchars($message) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
<?php
        else:
?>
        <div class="alert alert-<?= $alertClass ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($flashMessage) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
<?php
        endif;
    endif;
endforeach;
?>
