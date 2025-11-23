<?php
// === admin.php (Фінальна версія) ===

// 1. Завжди починаємо сесію
session_start();

// 2. Захист сторінки
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// 3. Підключаємо конфігурацію та функції
require_once 'config.php';
require_once 'functions.php'; // Підключаємо наш файл з новою функцією

// 4. *** НОВА ЛОГІКА ОБРОБКИ ДІЙ (Завдання 6) ***
// Ми перевіряємо, чи є в URL параметр 'action'
if (isset($_GET['action'])) {
    
    // === ДІЯ: ВИДАЛИТИ ===
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id_to_delete = (int)$_GET['id'];
        
        try {
            $stmt = $pdo->prepare("DELETE FROM responses WHERE id = ?");
            $stmt->execute([$id_to_delete]);
        } catch (PDOException $e) {
            error_log("Помилка видалення: " . $e->getMessage());
        }
        
        // Повертаємо адміна назад на admin.php (вже без ?action...)
        header("Location: admin.php");
        exit;
    }
    
    // === ДІЯ: ЕКСПОРТ ===
    if ($_GET['action'] == 'export') {
        // Викликаємо нашу нову функцію з functions.php
        export_responses_to_csv($pdo);
        // Функція сама зупинить скрипт (exit)
    }
}
// *** КІНЕЦЬ ЛОГІКИ ОБРОБКИ ДІЙ ***


// 5. Отримуємо ВСІ відповіді з бази (якщо це не була дія)
try {
    $stmt = $pdo->query("SELECT * FROM responses ORDER BY submission_time DESC");
    $responses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Помилка отримання даних: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta charset="UTF-8">
    <title>Адмін-панель: Відповіді</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f9f9f9; }
        .container { max-width: 1200px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h1 { text-align: center; }
        .header-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .btn { padding: 8px 12px; text-decoration: none; border-radius: 4px; color: white; }
        .btn-export { background-color: #28a745; } /* Зелений */
        .btn-logout { background-color: #6c757d; } /* Сірий */
        .btn-delete { background-color: #dc3545; font-size: 12px; } /* Червоний */
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        td.q3 { max-width: 300px; word-wrap: break-word; }
        td.actions { width: 80px; text-align: center; }
    </style>
    
    <script>
        function confirmDelete(id) {
            // "Confirm" - це стандартне вікно браузера "Так/Ні"
            if (confirm("Ви впевнені, що хочете видалити відповідь ID " + id + "?")) {
                // Якщо користувач натиснув "Так", переходимо за посиланням
                window.location.href = 'admin.php?action=delete&id=' + id;
            }
            // Якщо "Ні", нічого не робимо
        }
    </script>
</head>
<body>
    <div class="container">
        
        <div class="header-controls">
            <a href="admin.php?action=export" class="btn btn-export">Експортувати в CSV</a>
            
            <a href="logout.php" class="btn btn-logout">Вийти з системи</a>
        </div>
        
        <h1>Всі відповіді (<?php echo count($responses); ?>)</h1>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Час</th>
                    <th>Ім'я</th>
                    <th>Email</th>
                    <th>Питання 1</th>
                    <th>Питання 2</th>
                    <th class="q3">Питання 3</th>
                    <th>Дії</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($responses) > 0) {
                    foreach ($responses as $row) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['submission_time']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['question1']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['question2']) . "</td>";
                        echo "<td class='q3'>" . nl2br(htmlspecialchars($row['question3'])) . "</td>";
                        
                        // 9. КНОПКА ВИДАЛЕННЯ
                        // Вона не є посиланням, щоб запобігти випадковому натисканню
                        // Вона викликає нашу JavaScript-функцію confirmDelete()
                        echo "<td class='actions'>";
                        echo "<a href='#' onclick='event.preventDefault(); confirmDelete(" . $row['id'] . ");' class='btn btn-delete'>Видалити</a>";
                        echo "</td>";
                        
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Поки що немає жодної відповіді.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>