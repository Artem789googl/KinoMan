<?php
session_start();
session_destroy(); // Уничтожить сессию
header("Location: ../index.php"); // Перенаправить на страницу входа
exit();
?>
