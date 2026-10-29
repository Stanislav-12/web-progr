<?php
require 'db.php';

// Одна книга за id
$book = false;
$error = '';
$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);

if ($id === false) {
    $error = 'ID книги не вказано або він некоректний.';
} else {
    $sql = "SELECT * FROM books WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $book = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$book) {
        $error = 'Книгу з таким ID не знайдено.';
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Книга</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require 'menu.php'; ?>
<?php if ($error !== ''): ?>
    <p><?= e($error) ?></p>
<?php else: ?>
    <h1><?= e($book['title']) ?></h1>
    <p>ID: <?= $book['id'] ?></p>
    <p>Автор: <?= e($book['author']) ?></p>
    <p>Рік видання: <?= $book['year'] ?></p>
    <p>Опис: <?= e($book['description']) ?></p>
<?php endif; ?>
<p><a href="index.php">До каталогу</a></p>
</body>
</html>
