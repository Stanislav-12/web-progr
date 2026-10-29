<?php
declare(strict_types=1);
require __DIR__ . '/common.php';

$errors = [];
$title = '';
$author = '';
$year = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        checkCsrf();
        $title = postString('title');
        $author = postString('author');
        $year = postString('year');

        if ($title === '' || mb_strlen($title) > 100) {
            $errors[] = 'Назва: від 1 до 100 символів.';
        }
        if ($author === '' || mb_strlen($author) > 60) {
            $errors[] = 'Автор: від 1 до 60 символів.';
        }
        $yearNumber = ctype_digit($year)
            ? filter_var($year, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1450, 'max_range' => (int) date('Y')]])
            : false;
        if ($yearNumber === false) {
            $errors[] = 'Рік: ціле число від 1450 до ' . date('Y') . '.';
        }

        if ($errors === []) {
            $books = loadBooks(); // Перевіряємо наявний файл перед зміною
            $books[] = [
                'id' => newId($books),
                'title' => $title,
                'author' => $author,
                'year' => $yearNumber,
            ];
            saveBooks($books);
            header('Location: index.php', true, 303);
            exit;
        }
    } catch (RuntimeException $exception) {
        $errors[] = $exception->getMessage();
    }
}

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Додати книгу (XML)</title>
</head>
<body>
<h1>Нова книга (XML)</h1>
<?php foreach ($errors as $error): ?>
    <p role="alert"><?= e($error) ?></p>
<?php endforeach; ?>
<form method="post" action="add.php">
    <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
    <p>
        <label for="title">Назва</label><br>
        <input type="text" id="title" name="title" maxlength="100" required value="<?= e($title) ?>">
    </p>
    <p>
        <label for="author">Автор</label><br>
        <input type="text" id="author" name="author" maxlength="60" required value="<?= e($author) ?>">
    </p>
    <p>
        <label for="year">Рік видання</label><br>
        <input type="number" id="year" name="year" min="1450" max="<?= date('Y') ?>" required value="<?= e($year) ?>">
    </p>
    <button type="submit">Додати книгу</button>
</form>
<p><a href="index.php">До каталогу</a></p>
</body>
</html>
