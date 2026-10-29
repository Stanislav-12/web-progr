<?php
declare(strict_types=1);
require __DIR__ . '/common.php';

$books = [];
$error = null;
try {
    $books = loadBooks();
} catch (RuntimeException $exception) {
    http_response_code(500);
    $error = $exception->getMessage();
}
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог книг (JSON)</title>
</head>
<body>
<h1>Каталог книг у бібліотеці (JSON)</h1>
<p><a href="add.php">Додати книгу</a></p>
<?php if ($error !== null): ?>
    <p><?= e($error) ?></p>
<?php elseif ($books === []): ?>
    <p>Записів немає.</p>
<?php else: ?>
    <table>
        <thead>
        <tr><th scope="col">Назва</th><th scope="col">Автор</th><th scope="col">Рік</th><th scope="col">Дія</th></tr>
        </thead>
        <tbody>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><?= e($book['title']) ?></td>
                <td><?= e($book['author']) ?></td>
                <td><?= $book['year'] ?></td>
                <td><a href="edit.php?id=<?= e(rawurlencode($book['id'])) ?>">Редагувати</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</body>
</html>
