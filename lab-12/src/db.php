<?php
session_start();

try {
    $pdo = new PDO("mysql:host=MySQL-8.4;dbname=library;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Помилка підключення до бази даних: " . $e->getMessage();
    exit();
}

function e($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
