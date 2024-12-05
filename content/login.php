<?php
require_once('db.php');
header('Content-Type: application/json');

session_start();

// Получаем данные из формы
$login = $_POST['login'] ?? '';
$pass = $_POST['password'] ?? '';

if (empty($login) || empty($pass)) {
    echo json_encode(['success' => false, 'message' => 'Заполните все поля.']);
    exit();
}

// SQL-запрос (с защитой от SQL-инъекций)
$sql = "SELECT * FROM `polzovatel` WHERE login = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Проверяем пароль с помощью password_verify
    if (password_verify($pass, $row['parol'])) {
        $_SESSION['username'] = $row['login']; // Сохраняем пользователя в сессии

        echo json_encode(['success' => true, 'message' => "Добро пожаловать, " . $row['login'] . "!"]);
    }else {
        echo json_encode(['success' => false, 'message' => 'Неверный логин или пароль!']);
        
    }
    exit();
} 
exit();
?>