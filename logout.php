<?php
// === logout.php ===

session_start(); // Запускаємо сесію, щоб отримати до неї доступ

// Знищуємо всі дані сесії
$_SESSION = array();

// Знищуємо саму сесію
session_destroy();

// Перенаправляємо на сторінку логіну
header("Location: login.php");
exit;
?>