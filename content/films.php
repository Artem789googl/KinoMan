<?php
require_once('db.php'); // Подключение к базе данных
header('Content-Type: application/json');

// Получение выбранной даты из GET-запроса
$date = $_GET['date'] ?? date('Y-m-d'); // Если дата не указана, берём текущую

// SQL-запрос для получения фильмов с сеансами на указанную дату
$sql = "
    SELECT 
        film.id_film,
        film.nazvanie,
        film.prodolzhitelnost,
        film.poster,
        GROUP_CONCAT(DISTINCT TIME(seans.data_i_vremya) ORDER BY seans.data_i_vremya ASC SEPARATOR ', ') AS seans_times
    FROM film
    INNER JOIN seans ON film.id_film = seans.film_id_film
    WHERE DATE(seans.data_i_vremya) = ?
    GROUP BY film.id_film
    ORDER BY film.nazvanie ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();

// Проверка, есть ли фильмы
if ($result->num_rows > 0) {
    $films = [];
    while ($row = $result->fetch_assoc()) {
        $films[] = $row;
    }
    echo json_encode(['success' => true, 'films' => $films]);
} else {
    echo json_encode(['success' => false, 'message' => 'Фильмы на выбранную дату отсутствуют.']);
}
?>
