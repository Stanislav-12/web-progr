<?php
session_start();

// Очищаємо дані сесії та завершуємо її
$_SESSION = [];
session_destroy();

header('Location: index.php');
exit();
