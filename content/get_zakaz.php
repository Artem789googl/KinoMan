<?php
require_once('db.php');
header('Content-Type: application/json');
session_start();

// Проверяем авторизацию
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не авторизован.']);
    exit();
}

// Получаем данные текущего пользователя
$username = $_SESSION['username'];

// Получаем ID пользователя из базы
$sqlUser = "SELECT id_pol FROM polzovatel WHERE login = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("s", $username);
$stmtUser->execute();
$userResult = $stmtUser->get_result();
$user = $userResult->fetch_assoc();

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не найден.']);
    exit();
}

$userId = $user['id_pol'];

// Получаем заказы только для данного пользователя
$sqlOrders = "SELECT id_zakaz, kolichestvo_biletov, data_sozdaniya, summa FROM zakaz WHERE polzovatel_id_pol = ?";
$stmtOrders = $conn->prepare($sqlOrders);
$stmtOrders->bind_param("i", $userId);
$stmtOrders->execute();
$resultOrders = $stmtOrders->get_result();

$orders = [];
while ($row = $resultOrders->fetch_assoc()) {
    $orders[] = $row;
}

// Возвращаем данные
echo json_encode(['success' => true, 'data' => $orders]);

$stmtOrders->close();
$conn->close();
