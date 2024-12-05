<?php
require_once('db.php');
header('Content-Type: application/json');

// Включаем вывод ошибок для отладки
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Получаем ID пользователя (например, из сессии)
session_start();
$username = $_SESSION['username'];
$sqlUser = "SELECT id_pol FROM polzovatel WHERE login = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("s", $username);
$stmtUser->execute();
$userResult = $stmtUser->get_result();
$user = $userResult->fetch_assoc();
$userId = $user['id_pol'];

// Проверяем, есть ли что-то в корзине
$sqlCart = "SELECT * FROM cart WHERE polzovatel_id_pol = ?";
$stmtCart = $conn->prepare($sqlCart);
$stmtCart->bind_param("i", $userId);
$stmtCart->execute();
$resultCart = $stmtCart->get_result();

if ($resultCart->num_rows > 0) {
    // Инициализируем данные для заказа
    $totalTickets = 0;
    $totalSum = 0;
    $cartItems = [];

    while ($row = $resultCart->fetch_assoc()) {
        $totalTickets++;
        $totalSum += $row['stoimost'];
        $cartItems[] = $row; // Сохраняем данные из корзины
    }

    // Вставляем заказ в таблицу zakaz
    $sqlZakaz = "INSERT INTO zakaz (kolichestvo_biletov, data_sozdaniya, summa, polzovatel_id_pol) VALUES (?, NOW(), ?, ?)";
    $stmtZakaz = $conn->prepare($sqlZakaz);
    $stmtZakaz->bind_param("iii", $totalTickets, $totalSum, $userId);

    if ($stmtZakaz->execute()) {
        $zakazId = $stmtZakaz->insert_id; // Получаем ID нового заказа

        // Вставляем билеты в таблицу bilet
        $sqlBilet = "INSERT INTO bilet (ryad, mesto, tip_bileta, stoimost, zakaz_id_zakaz, seans_id_seans) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtBilet = $conn->prepare($sqlBilet);

        // Проходим по всем элементам корзины
        foreach ($cartItems as $item) {
            // Получаем время сеанса из корзины
            $sessionTime = $item['data_i_vremya'];

            // Ищем соответствующий seans_id_seans в таблице seans по времени сеанса
            $sqlSeans = "SELECT id_seans FROM seans WHERE data_i_vremya = ?";
            $stmtSeans = $conn->prepare($sqlSeans);
            $stmtSeans->bind_param("s", $sessionTime);
            $stmtSeans->execute();
            $resultSeans = $stmtSeans->get_result();

            if ($resultSeans->num_rows > 0) {
                $seans = $resultSeans->fetch_assoc();
                $seansId = $seans['id_seans']; // Получаем id_seans

                // Теперь вставляем билет в таблицу bilet
                $stmtBilet->bind_param(
                    "sssiii",
                    $item['ryad'],            // Ряд
                    $item['mesto'],           // Место
                    $item['type_bilet'],      // Тип билета
                    $item['stoimost'],        // Стоимость
                    $zakazId,                 // ID заказа
                    $seansId                  // ID сеанса
                );
                $stmtBilet->execute();
            } else {
                // Если не найден сеанс с таким временем
                echo json_encode(['success' => false, 'message' => 'Не найден сеанс с таким временем']);
                exit;
            }
        }

// Очистка корзины и дальнейшие действия


        // Очищаем корзину
        $sqlClearCart = "DELETE FROM cart WHERE polzovatel_id_pol = ?";
        $stmtClearCart = $conn->prepare($sqlClearCart);
        $stmtClearCart->bind_param("i", $userId);
        if ($stmtClearCart->execute()) {
            // Успешная очистка корзины
            echo json_encode(['success' => true, 'message' => 'Заказ успешно оформлен и корзина очищена']);
        } else {
            // Ошибка при очистке корзины
            echo json_encode(['success' => false, 'message' => 'Ошибка при очистке корзины']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Ошибка при создании заказа']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Корзина пуста']);
}
?>
