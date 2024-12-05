<?php
// Подключаем базу данных
require_once('db.php');

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $orderId = $_GET['id'];

    // Запрос на получение данных о заказе и связанных данных (сеанс, билеты, фильм)
    $query = "
        SELECT 
            zakaz.id_zakaz,
            zakaz.kolichestvo_biletov,
            zakaz.summa,
            zakaz.data_sozdaniya,
            bilet.ryad,
            bilet.mesto,
            bilet.tip_bileta,
            bilet.stoimost,
            seans.data_i_vremya,
            seans.zal,
            film.nazvanie AS film_nazvanie,
            film.poster AS film_poster
        FROM zakaz
        JOIN bilet ON zakaz.id_zakaz = bilet.zakaz_id_zakaz
        JOIN seans ON bilet.seans_id_seans = seans.id_seans
        JOIN film ON seans.film_id_film = film.id_film
        WHERE zakaz.id_zakaz = ?
    ";

    // Подготавливаем запрос
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode([
            'success' => false,
            'message' => 'Ошибка подготовки запроса: ' . $conn->error
        ]);
        exit();
    }

    // Привязываем параметр
    $stmt->bind_param("i", $orderId);

    // Выполняем запрос
    $stmt->execute();

    // Получаем результат
    $result = $stmt->get_result();
    $orderDetails = [];
    while ($row = $result->fetch_assoc()) {
        $orderDetails[] = $row;
    }

    // Проверяем, есть ли данные
    if (!empty($orderDetails)) {
        echo json_encode([
            'success' => true,
            'data' => $orderDetails
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Заказ не найден.'
        ]);
    }

    // Закрываем запрос
    $stmt->close();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'ID заказа не указан.'
    ]);
}

// Закрываем соединение с базой данных
$conn->close();
?>
