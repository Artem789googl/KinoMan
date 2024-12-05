<?php

require_once('db.php');
header('Content-Type: application/json');

session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не авторизован.']);
    exit();
}

// Получение данных из POST-запроса
$seansId = $_POST['seans_id'] ?? null;
$filmId = $_POST['film_id'] ?? null;
$zal = $_POST['zal'] ?? null;
$row = $_POST['row'] ?? null;
$seat = $_POST['seat'] ?? null;
$typeBilet = $_POST['type_bilet'] ?? null;
$stoimost = $_POST['stoimost'] ?? null;
$dataTime = $_POST['data_time'] ?? null;

// Получение ID пользователя
$username = $_SESSION['username'];
$sqlUser = "SELECT id_pol FROM polzovatel WHERE login = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("s", $username);
$stmtUser->execute();
$userResult = $stmtUser->get_result();
$user = $userResult->fetch_assoc();
$userId = $user['id_pol'];

// Проверка данных
if (!$seansId || !$filmId || !$zal || !$row || !$seat || !$typeBilet || !$stoimost || !$dataTime || !$userId) {
    echo json_encode(['success' => false, 'message' => 'Некорректные данные.']);
    exit();
}

// Получаем название фильма по ID
$sqlFilm = "SELECT nazvanie FROM film WHERE id_film = ?";
$stmtFilm = $conn->prepare($sqlFilm);
$stmtFilm->bind_param("i", $filmId);
$stmtFilm->execute();
$filmResult = $stmtFilm->get_result();
$film = $filmResult->fetch_assoc();
$filmName = $film['nazvanie'];

$sqlInsert = "INSERT INTO cart (naz_film, type_bilet, zal, ryad, mesto, data_i_vremya, stoimost, polzovatel_id_pol)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmtInsert = $conn->prepare($sqlInsert);
$stmtInsert->bind_param("ssiiisii", $filmName, $typeBilet, $zal, $row, $seat, $dataTime, $stoimost, $userId);

if ($stmtInsert->execute()) {
    echo json_encode(['success' => true, 'message' => 'Фильм добавлен в корзину.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка при добавлении в корзину.']);
}
?>
