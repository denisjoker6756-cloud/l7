<?php
// === config.php ===

// Вмикаємо показ помилок (для розробки)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Налаштування Бази Даних
define('DB_HOST', 'localhost'); // Сервер (зазвичай 'localhost' для XAMPP)
define('DB_USER', 'root');      // Ім'я користувача БД (XAMPP за замовчуванням 'root')
define('DB_PASS', '');          // Пароль (XAMPP за замовчуванням - порожній)
define('DB_NAME', 'lab6_survey'); // Ім'я нашої бази даних

// Встановлюємо часовий пояс
date_default_timezone_set('Europe/Kiev');

// Спроба підключення до БД
try {
    // Створюємо об'єкт PDO (сучасний спосіб роботи з БД у PHP)
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    
    // Встановлюємо режим помилок, щоб бачити проблеми з SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // Якщо підключення не вдалося, зупиняємо скрипт і показуємо помилку
    die("Помилка підключення до бази даних: " . $e->getMessage());
}

// Змінна для повідомлень
$message = "";
?>