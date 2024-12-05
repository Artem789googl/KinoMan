<?php
// Подключение к базе данных
require_once 'db.php';

// Получаем данные из POST-запроса
$data = json_decode(file_get_contents('php://input'), true);

header('Content-Type: application/json');

// Проверяем наличие необходимых данных
if (isset($data['film_id']) && isset($data['date'])) {
    $filmId = $data['film_id'];
    $date = $data['date'];

    // Начинаем транзакцию
    $conn->begin_transaction();

    try {
        // Удаляем билеты, связанные с сеансами указанного фильма на указанную дату
        $stmt1 = $conn->prepare("
            DELETE b 
            FROM bilet b
            JOIN seans s ON b.seans_id_seans = s.id_seans
            WHERE s.film_id_film = ? AND DATE(s.data_i_vremya) = ?
        ");
        $stmt1->bind_param("is", $filmId, $date);
        $stmt1->execute();

        // Удаляем сеансы, связанные с указанным фильмом на указанную дату
        $stmt2 = $conn->prepare("
            DELETE FROM seans
            WHERE film_id_film = ? AND DATE(data_i_vremya) = ?
        ");
        $stmt2->bind_param("is", $filmId, $date);
        $stmt2->execute();

        // Удаляем сам фильм
        $stmt3 = $conn->prepare("DELETE FROM film WHERE id_film = ?");
        $stmt3->bind_param("i", $filmId);
        $stmt3->execute();

        // Подтверждаем транзакцию
        $conn->commit();

        echo json_encode(['success' => true, 'message' => 'Фильм, его сеансы и связанные билеты успешно удалены!']);
    } catch (Exception $e) {
        // В случае ошибки откатываем транзакцию
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Ошибка при удалении: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Не переданы все необходимые данные.']);
}
?>
