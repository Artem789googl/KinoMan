<?php
require_once('db.php'); // Подключение к базе данных

// Проверка соединения
if (!$conn) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

$action = $_GET['action'] ?? 'view'; // Определяем действие

if ($action === 'get_films_by_date') {
    // Получение фильмов по дате
    $selectedDate = $_GET['date'] ?? '';
    if (empty($selectedDate)) {
        echo json_encode(['success' => false, 'message' => 'Дата не указана.']);
        exit();
    }

    $sql = "SELECT f.id_film, f.nazvanie, f.poster 
            FROM film f 
            JOIN seans s ON f.id_film = s.film_id_film 
            WHERE DATE(s.data_i_vremya) = ?
            GROUP BY f.id_film";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedDate);
    $stmt->execute();
    $result = $stmt->get_result();

    $films = [];
    while ($row = $result->fetch_assoc()) {
        $films[] = $row;
    }

    echo json_encode(['success' => true, 'films' => $films]);
    exit();

} elseif ($action === 'delete_film') {
    // Удаление фильма и сеансов
    $filmId = $_POST['film_id'] ?? 0;
    $selectedDate = $_POST['date'] ?? '';

    if (empty($filmId) || empty($selectedDate)) {
        echo json_encode(['success' => false, 'message' => 'Некорректные данные.']);
        exit();
    }

    $conn->begin_transaction();

    try {
        // Удаляем сеансы фильма на указанную дату
        $sqlDeleteSeans = "DELETE FROM seans WHERE film_id_film = ? AND DATE(data_i_vremya) = ?";
        $stmtDeleteSeans = $conn->prepare($sqlDeleteSeans);
        $stmtDeleteSeans->bind_param("is", $filmId, $selectedDate);
        $stmtDeleteSeans->execute();

        // Проверяем: если у фильма больше нет сеансов, удаляем фильм
        $sqlCheckSeans = "SELECT COUNT(*) as count FROM seans WHERE film_id_film = ?";
        $stmtCheckSeans = $conn->prepare($sqlCheckSeans);
        $stmtCheckSeans->bind_param("i", $filmId);
        $stmtCheckSeans->execute();
        $resultCheckSeans = $stmtCheckSeans->get_result()->fetch_assoc();

        if ($resultCheckSeans['count'] == 0) {
            $sqlDeleteFilm = "DELETE FROM film WHERE id_film = ?";
            $stmtDeleteFilm = $conn->prepare($sqlDeleteFilm);
            $stmtDeleteFilm->bind_param("i", $filmId);
            $stmtDeleteFilm->execute();
        }

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Фильм и связанные сеансы удалены.']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit();
}

// Основной блок для вывода данных фильма и сеансов
$filmId = $_GET['id_film'] ?? 0;

if ($filmId == 0) {
    die("Фильм не найден. Неверный ID.");
}

// Получаем данные о фильме
$sqlFilm = "SELECT * FROM film WHERE id_film = ?";
$stmtFilm = $conn->prepare($sqlFilm);
$stmtFilm->bind_param("i", $filmId);
$stmtFilm->execute();
$resultFilm = $stmtFilm->get_result();

if ($resultFilm->num_rows === 0) {
    die("Фильм с ID $filmId не найден в базе данных.");
}

$film = $resultFilm->fetch_assoc();

// Получаем сеансы для фильма
$sqlSeans = "SELECT * FROM seans WHERE film_id_film = ? ORDER BY data_i_vremya";
$stmtSeans = $conn->prepare($sqlSeans);
$stmtSeans->bind_param("i", $filmId);
$stmtSeans->execute();
$resultSeans = $stmtSeans->get_result();

$seanses = [];
while ($row = $resultSeans->fetch_assoc()) {
    $seanses[] = $row;
}
?>
