<?php
// Define message types and their corresponding Bootstrap alert classes
$messageTypes = [
    'success' => 'alert-success',
    'error' => 'alert-danger',
    'warning' => 'alert-warning',
    'info' => 'alert-info'
];

// Check if session function/helper exists
if (function_exists('session')) {
    // Loop through each message type
    foreach ($messageTypes as $type => $alertClass) {
        // Get flash messages from session
        $messages = session()->getFlash($type);
        
        // If messages exist (can be string or array)
        if (!empty($messages)) {
            // Convert string to array for consistent handling
            if (is_string($messages)) {
                $messages = [$messages];
            }
            
            // Display each message with appropriate styling
            foreach ($messages as $message) {
                if (!empty($message)) {
                    echo '<div class="alert ' . $alertClass . ' alert-dismissible fade show" role="alert">';
                    echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
                    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '</div>';
                }
            }
        }
    }
}
