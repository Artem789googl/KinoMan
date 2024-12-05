<?php
// Подключаемся к базе данных
require_once('db.php');

// Проверяем, передан ли ID корзины
if (isset($_POST['cart_id'])) {
    $cartId = $_POST['cart_id'];

    // Подготавливаем запрос на удаление
    $sqlDelete = "DELETE FROM cart WHERE id_cart = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $cartId);

    // Выполняем запрос
    if ($stmtDelete->execute()) {
        echo json_encode(['success' => true, 'message' => 'Элемент удален из корзины']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Ошибка при удалении элемента']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Не передан ID корзины']);
}
?>
