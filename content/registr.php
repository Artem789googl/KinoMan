<?php
require_once('db.php');
header('Content-Type: application/json'); // Заголовок для JSON-ответа

session_start();
$contact = $_POST['contact'] ?? '';
$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';
$repeatpassword = $_POST['repeatpassword'] ?? '';

// Проверка на пустые поля
if (empty($login) || empty($password) || empty($repeatpassword) || empty($contact)) {
    echo json_encode(['success' => false, 'message' => 'Заполните все поля.']);
    exit();
}

//Проверка на длину пароля
if (strlen($password) <= 8){
    echo json_encode(['success' => false, 'message' => 'Пароли меньше 8 символов.']);
    exit();
}


// Проверка совпадения паролей
if ($password !== $repeatpassword) {
    echo json_encode(['success' => false, 'message' => 'Пароли не совпадают.']);
    exit();
}

// Проверка на существование логина
$sql = "SELECT COUNT(*) FROM polzovatel WHERE login = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Ошибка базы данных: ' . $conn->error]);
    exit();
}
$stmt->bind_param("s", $login);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count > 0){
    echo json_encode(['success' => false, 'message' => 'Пользователь с данным именем уже существует!']);
    exit();
}

// Хеширование пароля
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Подготовка запроса
$sql = "INSERT INTO polzovatel (login, parol, contact) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Ошибка базы данных: ' . $conn->error]);
    exit();
}



$stmt->bind_param("sss", $login, $hashedPassword, $contact);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Регистрация успешна!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка при регистрации: ' . $stmt->error]);
}

// SQL-запрос (с защитой от SQL-инъекций)
$sql = "SELECT * FROM `polzovatel` WHERE login = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();

$_SESSION['username'] = $login; 

$stmt->close();
$conn->close(); // Закрываем соединение с базой данных
exit();
?>