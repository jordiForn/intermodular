<?php
session_start();
if (isset($_POST['cart_items'])) {
    $cart = json_decode($_POST['cart_items'], true);
    $_SESSION['cart'] = [];
    foreach ($cart as $item) {
        $_SESSION['cart'][$item['id']] = $item['quantity'];
    }
    echo 'ok';
}