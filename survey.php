<?php
// === survey.php ===
// Тут більше немає PHP-коду для обробки POST запиту. 
// Ми залишаємо тільки HTML.
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторна 7 - AJAX Анкета</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h1 { text-align: center; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input[type="text"], input[type="email"], textarea, select {
            width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;
        }
        input[type="submit"] {
            background: #007BFF; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 20px;
        }
        input[type="submit"]:hover { background: #0056b3; }
        
        /* Стилі для повідомлень */
        #response-message { margin-bottom: 20px; display: none; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 15px; border-radius: 4px; text-align: center; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 15px; border-radius: 4px; text-align: center; }
    </style>
</head>
<body>

    <div class="container">
        <h1>Анкета (AJAX)</h1>

        <div id="response-message"></div>
        
        <form id="surveyForm">
            
            <label for="name">Ваше ім'я:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Ваш Email:</label>
            <input type="email" id="email" name="email" required>

            <hr>
            
            <label for="question1">1. Яка ваша улюблена технологія frontend?</label>
            <select id="question1" name="question1" required>
                <option value="">-- Оберіть --</option>
                <option value="React">React</option>
                <option value="Vue">Vue</option>
                <option value="Angular">Angular</option>
                <option value="Svelte">Svelte</option>
                <option value="Чистий JS">Чистий JS/HTML/CSS</option>
            </select>

            <label>2. Як ви оцінюєте цей курс?</label>
            <input type="radio" id="q2_5" name="question2" value="5 - Чудово" required> <label for="q2_5">5 - Чудово</label><br>
            <input type="radio" id="q2_4" name="question2" value="4 - Добре"> <label for="q2_4">4 - Добре</label><br>
            <input type="radio" id="q2_3" name="question2" value="3 - Задовільно"> <label for="q2_3">3 - Задовільно</label><br>
            <input type="radio" id="q2_2" name="question2" value="2 - Погано"> <label for="q2_2">2 - Погано</label><br>
            
            <label for="question3">3. Що б ви хотіли додати до курсу?</label>
            <textarea id="question3" name="question3" rows="4" required></textarea>

            <input type="submit" value="Надіслати відгук">
        </form>
        
    </div>

    <script>
        $(document).ready(function() {
            // Перехоплюємо подію відправки форми
            $("#surveyForm").submit(function(event) {
                
                // 1. Зупиняємо стандартну відправку (щоб сторінка не перезавантажилась)
                event.preventDefault();

                // 2. Збираємо дані з форми
                var formData = $(this).serialize();

                // 3. Відправляємо AJAX запит
                $.ajax({
                    type: "POST",
                    url: "submit.php", // Відправляємо на наш новий файл-обробник
                    data: formData,
                    success: function(response) {
                        // Отримуємо відповідь від сервера
                        // Ми домовились, що формат буде "тип|повідомлення"
                        var parts = response.split("|");
                        var type = parts[0];
                        var msg = parts[1];

                        var messageBox = $("#response-message");

                        if (type === "success") {
                            // Успіх
                            messageBox.removeClass("error").addClass("success");
                            messageBox.html("<h2>Дякуємо!</h2><p>Дані збережено: " + msg + "</p>");
                            messageBox.fadeIn();
                            
                            // Ховаємо форму
                            $("#surveyForm").slideUp(); 
                        } else {
                            // Помилка
                            messageBox.removeClass("success").addClass("error");
                            messageBox.text("Помилка: " + msg);
                            messageBox.fadeIn();
                        }
                    },
                    error: function() {
                        alert("Сталася помилка з'єднання з сервером.");
                    }
                });
            });
        });
    </script>

</body>
</html>