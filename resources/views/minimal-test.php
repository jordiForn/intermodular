<div style="padding: 20px; background-color: #e6ffed; border-radius: 5px; margin-bottom: 20px;">
    <h2>Minimal Test View</h2>
    <p>This is a minimal test view with no dependencies.</p>
    <p>Current time: <?= date('Y-m-d H:i:s') ?></p>
    
    <?php if (isset($message)): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-top: 10px;">
            <strong>Message:</strong> <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
</div>
