<?php
require_once('db.php'); // Подключение к базе данных
header('Content-Type: application/json');

// Получение данных из формы
$nazvanie = $_POST['filmTitle'] ?? '';
$vozrast_ogranich = $_POST['ageRating'] ?? '';
$prodolzhitelnost = $_POST['duration'] ?? '';
$strana = $_POST['production'] ?? '';
$rezhisser = $_POST['director'] ?? '';
$desc = $_POST['description'] ?? '';
$trailer = $_POST['trailerLink'] ?? '';
$god_create = $_POST['releaseYear'] ?? '';
$zal = $_POST['zal'] ?? '';
$data_i_vremya = ($_POST['dateivremya'] ?? '') . ' ' . ($_POST['vremya'] ?? '');

// Проверка даты и времени
if (empty($_POST['dateivremya']) || empty($_POST['vremya'])) {
    echo json_encode(['success' => false, 'message' => 'Укажите корректные дату и время.']);
    exit();
}

// Проверка загрузки изображения
if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../img/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $posterName = basename($_FILES['imageUpload']['name']);
    $posterPath = $uploadDir . $posterName;

    if (!move_uploaded_file($_FILES['imageUpload']['tmp_name'], $posterPath)) {
        echo json_encode(['success' => false, 'message' => 'Ошибка загрузки изображения.']);
        exit();
    }

    $poster = $posterName; // Сохраняем только имя файла
} else {
    echo json_encode(['success' => false, 'message' => 'Необходимо загрузить постер.']);
    exit();
}

// Проверка обязательных полей
if (empty($nazvanie) || empty($vozrast_ogranich) || empty($prodolzhitelnost) || empty($strana) || empty($rezhisser) || empty($god_create) || empty($zal)) {
    echo json_encode(['success' => false, 'message' => 'Заполните все обязательные поля.']);
    exit();
}

$conn->begin_transaction(); // Начало транзакции

try {
    // Проверяем, существует ли фильм с таким названием
    $sqlCheckFilm = "SELECT id_film FROM film WHERE nazvanie = ?";
    $stmtCheckFilm = $conn->prepare($sqlCheckFilm);
    $stmtCheckFilm->bind_param("s", $nazvanie);
    $stmtCheckFilm->execute();
    $resultCheckFilm = $stmtCheckFilm->get_result();

    if ($resultCheckFilm->num_rows > 0) {
        // Фильм существует
        $film = $resultCheckFilm->fetch_assoc();
        $filmId = $film['id_film'];

        // Проверяем, существует ли уже сеанс с такой датой и фильмом
        $sqlCheckSeans = "SELECT id_seans FROM seans WHERE data_i_vremya = ? AND film_id_film = ?";
        $stmtCheckSeans = $conn->prepare($sqlCheckSeans);
        $stmtCheckSeans->bind_param("si", $data_i_vremya, $filmId);
        $stmtCheckSeans->execute();
        $resultCheckSeans = $stmtCheckSeans->get_result();

        if ($resultCheckSeans->num_rows > 0) {
            throw new Exception('Сеанс с такой датой уже существует.');
        }

        // Добавляем новый сеанс
        $sqlSeans = "INSERT INTO seans (data_i_vremya, zal, film_id_film) VALUES (?, ?, ?)";
        $stmtSeans = $conn->prepare($sqlSeans);
        $stmtSeans->bind_param("ssi", $data_i_vremya, $zal, $filmId);

        if (!$stmtSeans->execute()) {
            throw new Exception('Ошибка добавления сеанса: ' . $stmtSeans->error);
        }

    } else {
        // Если фильма нет, добавляем фильм и сеанс
        $sqlFilm = "INSERT INTO film (nazvanie, vozrast_ogranich, prodolzhitelnost, strana, rezhisser, `desc`, poster, trailer, god_create) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtFilm = $conn->prepare($sqlFilm);
        $stmtFilm->bind_param("siissssss", $nazvanie, $vozrast_ogranich, $prodolzhitelnost, $strana, $rezhisser, $desc, $poster, $trailer, $god_create);

        if (!$stmtFilm->execute()) {
            throw new Exception('Ошибка добавления фильма: ' . $stmtFilm->error);
        }

        $filmId = $conn->insert_id; // ID фильма

        $sqlSeans = "INSERT INTO seans (data_i_vremya, zal, film_id_film) VALUES (?, ?, ?)";
        $stmtSeans = $conn->prepare($sqlSeans);
        $stmtSeans->bind_param("ssi", $data_i_vremya, $zal, $filmId);

        if (!$stmtSeans->execute()) {
            throw new Exception('Ошибка добавления сеанса: ' . $stmtSeans->error);
        }
    }

    $conn->commit(); // Подтверждение транзакции
    echo json_encode(['success' => true, 'message' => 'Данные успешно добавлены.']);
} catch (Exception $e) {
    $conn->rollback(); // Откат транзакции
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
