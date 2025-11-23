<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Бонус: Робота з API (jQuery)</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; text-align: center; }
        table { width: 80%; margin: 20px auto; border-collapse: collapse; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #007BFF; color: white; cursor: pointer; }
        th:hover { background-color: #0056b3; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        
        .controls { margin-bottom: 20px; }
        button { 
            padding: 10px 15px; margin: 5px; cursor: pointer; 
            background: #28a745; color: white; border: none; border-radius: 4px; font-size: 14px; 
        }
        button:hover { opacity: 0.9; }
        button.sort-btn { background: #17a2b8; }
    </style>
</head>
<body>

    <h1>Список персонажів (API VNTU)</h1>

    <div class="controls">
        <button id="load-btn">🔄 Завантажити / Оновити дані</button>
        <button class="sort-btn" data-sort="name">Сортувати за Іменем</button>
        <button class="sort-btn" data-sort="affiliation">Сортувати за Фракцією</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Ім'я (Name)</th>
                <th>Фракція (Affiliation)</th>
                <th>Ранг (Rank)</th>
            </tr>
        </thead>
        <tbody id="api-table-body">
            <tr><td colspan="4">Натисніть кнопку "Оновити дані"...</td></tr>
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            
            // Змінна для збереження завантажених даних
            var cachedData = [];

            // Функція для відображення даних у таблиці
            function renderTable(data) {
                let tbody = $("#api-table-body");
                tbody.empty();

                $.each(data, function(index, item) {
                    let row = "<tr>" +
                        "<td>" + item.id + "</td>" +
                        "<td>" + item.name + "</td>" +
                        "<td>" + item.affiliation + "</td>" +
                        "<td>" + item.rank + "</td>" +
                        "</tr>";
                    tbody.append(row);
                });
            }

            // Функція завантаження з сервера
            function loadData() {
                $("#api-table-body").html("<tr><td colspan='4'>Завантаження даних...</td></tr>");
                
                $.ajax({
                    url: "http://lab.vntu.org/api-server/lab7.php",
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        // Зберігаємо отримані дані у змінну
                        cachedData = data;
                        // Відображаємо як є (без сортування спочатку)
                        renderTable(cachedData);
                    },
                    error: function(xhr, status, error) {
                        console.error("Помилка:", error);
                        $("#api-table-body").html("<tr><td colspan='4' style='color:red;'>Помилка завантаження!</td></tr>");
                    }
                });
            }

            // Функція сортування (Клієнтська - JavaScript)
            function sortData(field) {
                if (cachedData.length === 0) {
                    alert("Спочатку завантажте дані!");
                    return;
                }

                // Сортуємо масив об'єктів
                cachedData.sort(function(a, b) {
                    // Отримуємо значення полів і переводимо в нижній регістр для коректного порівняння
                    let valA = a[field].toLowerCase();
                    let valB = b[field].toLowerCase();

                    if (valA < valB) return -1; // a йде перед b
                    if (valA > valB) return 1;  // a йде після b
                    return 0; // рівні
                });

                // Перемальовуємо таблицю вже з відсортованими даними
                renderTable(cachedData);
            }

            // --- ОБРОБНИКИ ПОДІЙ ---

            // Кнопка завантаження
            $("#load-btn").click(function() {
                loadData();
            });

            // Кнопки сортування
            $(".sort-btn").click(function() {
                let sortType = $(this).data("sort"); // 'name' або 'affiliation'
                sortData(sortType);
            });
        });
    </script>

</body>
</html>