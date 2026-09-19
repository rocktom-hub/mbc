<?php
// logout.php
require_once __DIR__ . '/config/helpers.php';

session_unset();
session_destroy();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

setFlash('info', 'You have been logged out successfully.');
header('Location: ' . baseUrl('login.php'));
exit;
