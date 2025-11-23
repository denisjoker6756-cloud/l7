<?php
// === login.php ===

// 1. Сесію треба запускати на *кожній* сторінці, де ми її використовуємо
session_start();

// 2. Підключаємо конфіг (він нам не дуже потрібен, але для порядку)
require_once 'config.php'; 

// 3. Встановлюємо "секретний" логін та пароль
// У реальному проєкті вони були б у базі даних
define('ADMIN_LOGIN', 'admin');
define('ADMIN_PASSWORD', 'password123'); // Зміни це на свій пароль

// 4. Перевіряємо, чи була форма надіслана
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $password = $_POST['password'];

    // 5. Перевіряємо, чи збігаються дані
    if ($login === ADMIN_LOGIN && $password === ADMIN_PASSWORD) {
        // Успіх! Створюємо "пропуск" (змінну сесії)
        $_SESSION['admin_logged_in'] = true;
        
        // Перенаправляємо адміна на захищену сторінку
        header("Location: admin.php");
        exit;
    } else {
        // Невдача. Встановлюємо повідомлення про помилку
        $message = "<div class='error'>Неправильний логін або пароль!</div>";
    }
}

// 6. Якщо адмін ВЖЕ увійшов, не показуємо йому форму, а одразу кидаємо в адмінку
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="uk">
<head><script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta charset="UTF-8">
    <title>Вхід в Адмін-панель</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background: #f4f4f4; }
        .login-container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 300px; }
        h1 { text-align: center; margin-top: 0; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input[type="text"], input[type="password"] { width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        input[type="submit"] { background: #007BFF; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 20px; width: 100%; }
        input[type="submit"]:hover { background: #0056b3; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 4px; text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Вхід</h1>
        <?php echo $message ?? ''; // Виводимо помилку, якщо вона є ?>
        <form action="login.php" method="POST">
            <label for="login">Логін:</label>
            <input type="text" id="login" name="login" required>

            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Увійти">
        </form>
    </div>
</body>
</html>