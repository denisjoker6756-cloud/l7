<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Генератор жартів (AJAX)</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
        #joke-box { 
            margin: 20px auto; 
            padding: 20px; 
            border: 1px solid #ccc; 
            width: 50%; 
            background: #f9f9f9; 
            border-radius: 8px;
            min-height: 50px;
            font-style: italic;
        }
        button { padding: 10px 20px; cursor: pointer; background: #007BFF; color: white; border: none; border-radius: 4px; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>

    <h1>Хвилинка гумору</h1>
    <button id="load-joke">Розповісти жарт</button>

    <div id="joke-box">Натисніть кнопку, щоб отримати жарт!</div>

    <script>
        $(document).ready(function() {
            $("#load-joke").click(function() {
                // Виконуємо AJAX запит до файлу
                $.get("jokes.txt", function(data) {
                    // Розбиваємо текст на масив рядків (по ентеру \n)
                    var lines = data.split("\n");
                    // Обираємо випадковий рядок
                    var randomLine = lines[Math.floor(Math.random() * lines.length)];
                    // Якщо рядок порожній (наприклад, в кінці файлу), пробуємо ще раз або виводимо як є
                    if(randomLine.trim() !== "") {
                        $("#joke-box").text(randomLine);
                    }
                });
            });
        });
    </script>
</body>
</html>