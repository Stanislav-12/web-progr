<?php
require 'db.php';

// Список книг
$sql = "SELECT id, title, author, year FROM books ORDER BY title";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Каталог книг</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require 'menu.php'; ?>
<h1>Каталог книг у бібліотеці</h1>
<?php if (count($books) > 0): ?>
    <table>
        <tr><th>Назва</th><th>Автор</th><th>Рік</th></tr>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><a href="book.php?id=<?= $book['id'] ?>"><?= e($book['title']) ?></a></td>
                <td><?= e($book['author']) ?></td>
                <td><?= $book['year'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>У каталозі немає книг.</p>
<?php endif; ?>
</body>
</html>
