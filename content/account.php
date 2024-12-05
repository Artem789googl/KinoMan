<?php
require_once('db.php');
header('Content-Type: application/json');

session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не авторизован.']);
    exit();
}

// Получение логина из сессии
$login = $_SESSION['username'];

// SQL-запрос для получения данных пользователя
$sql = "SELECT id_pol, login, fio, contact, privelegia, ikonka FROM `polzovatel` WHERE login = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode(['success' => true, 'data' => $user]);
} else {
    echo json_encode(['success' => false, 'message' => 'Пользователь не найден.']);
}

$stmt->close();
$conn->close();
