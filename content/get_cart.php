<?php
session_start();
require_once('db.php');

// Проверяем, что пользователь авторизован
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не авторизован.']);
    exit();
}

// Получаем id пользователя
$username = $_SESSION['username'];
$sqlUser = "SELECT id_pol FROM polzovatel WHERE login = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("s", $username);
$stmtUser->execute();
$userResult = $stmtUser->get_result();
$user = $userResult->fetch_assoc();
$userId = $user['id_pol'];

// Запрос для получения всех фильмов из корзины
$sql = "SELECT * FROM cart WHERE polzovatel_id_pol = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
}

echo json_encode(['success' => true, 'data' => $cartItems]);
?>
