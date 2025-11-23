<?php
// === functions.php (Оновлена версія) ===

/**
 * Функція для збереження відповіді в базу даних.
 * @param PDO $pdo - Об'єкт підключення до БД.
 * @param string $name - Ім'я респондента.
 * @param string $email - Email респондента.
 * @param string $q1 - Відповідь на питання 1.
 * @param string $q2 - Відповідь на питання 2.
 * @param string $q3 - Відповідь на питання 3.
 * @return string|false - Час відправки у разі успіху, або false у разі помилки.
 */
function save_survey_response(PDO $pdo, $name, $email, $q1, $q2, $q3) {
    
    $submission_time = date('Y-m-d H:i:s');

    try {
        // 1. Готуємо SQL-запит
        $sql = "INSERT INTO responses (name, email, question1, question2, question3, submission_time) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        
        // 2. Виконуємо запит
        $stmt->execute([$name, $email, $q1, $q2, $q3, $submission_time]);
        
        // 3. *** НОВА ПЕРЕВІРКА ***
        // Ми перевіряємо, чи був *насправді* доданий 1 рядок.
        // rowCount() повертає кількість рядків, яких торкнувся запит.
        if ($stmt->rowCount() === 1) {
            // Все добре, рядок додано!
            return $submission_time;
        } else {
            // Помилка: execute() спрацював, але нічого не додав
            error_log("Помилка запису в БД: rowCount() не дорівнює 1.");
            return false;
        }

    } catch (PDOException $e) {
        // Помилка: execute() провалився і видав виняток (Exception)
        error_log("Помилка запису в БД (Exception): " . $e->getMessage());
        return false;
    }
}
?>
<?php
// === functions.php ===

// ... (тут твій існуючий код, наприклад save_survey_response) ...


/**
 * Функція для експорту всіх відповідей у CSV-файл.
 * @param PDO $pdo - Об'єкт підключення до БД.
 */
function export_responses_to_csv(PDO $pdo) {
    
    // 1. Встановлюємо ім'я файлу
    $filename = "responses_export_" . date('Y-m-d') . ".csv";

    // 2. Встановлюємо HTTP-заголовки, щоб браузер завантажив файл
    // 'Content-Type: text/csv' - каже, що це CSV-файл
    header('Content-Type: text/csv; charset=utf-8');
    // 'Content-Disposition: attachment' - каже, що файл треба завантажити, а не показати
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    // 3. Відкриваємо "вихідний потік" PHP для запису
    $output = fopen('php://output', 'w');
    
    // 4. Додаємо BOM (Byte Order Mark), щоб Excel/інші програми правильно читали українські літери
    fputs($output, "\xEF\xBB\xBF");
    
    // 5. Записуємо рядок-заголовок (назви стовпців)
    fputcsv($output, ['ID', 'Час', 'Ім\'я', 'Email', 'Питання 1', 'Питання 2', 'Питання 3']);
    
    // 6. Отримуємо дані з БД
    try {
        $stmt = $pdo->query("SELECT id, submission_time, name, email, question1, question2, question3 FROM responses ORDER BY id ASC");
        
        // 7. Записуємо кожен рядок з БД у CSV-файл
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($output, $row);
        }
    } catch (PDOException $e) {
        error_log("Помилка експорту: " . $e->getMessage());
    }
    
    // 8. Закриваємо потік
    fclose($output);
    
    // 9. Обов'язково зупиняємо скрипт, щоб далі не рендерився HTML-код адмінки
    exit;
}
?>