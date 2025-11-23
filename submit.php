<?php
// === submit.php (Обробник для AJAX) ===

require_once 'config.php'; 
require_once 'functions.php';

// Перевіряємо, чи прийшли дані
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Отримуємо та чистимо дані
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $q1 = htmlspecialchars($_POST['question1'] ?? '');
    $q2 = htmlspecialchars($_POST['question2'] ?? '');
    $q3 = htmlspecialchars($_POST['question3'] ?? '');

    // Валідація (спрощена)
    if ($name && $email && $q1 && $q2 && $q3) {
        // Зберігаємо в БД
        $time_saved = save_survey_response($pdo, $name, $email, $q1, $q2, $q3);

        if ($time_saved) {
            // Повертаємо успішну відповідь (JSON або текст)
            echo "success|" . $time_saved; 
        } else {
            echo "error|Не вдалося зберегти в БД";
        }
    } else {
        echo "error|Заповніть всі поля!";
    }
}
?>