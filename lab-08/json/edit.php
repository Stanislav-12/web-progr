<?php
declare(strict_types=1);
require __DIR__ . '/common.php';

$errors = [];
$notFound = false;
$title = '';
$author = '';
$year = '';

// id приходить з посилання (GET), у формі він теж є в адресі action
$id = $_GET['id'] ?? '';
if (!is_string($id)) {
    $id = '';
}

try {
    $books = loadBooks();

    // Шукаємо книгу з таким id
    $index = null;
    foreach ($books as $i => $book) {
        if ($book['id'] === $id) {
            $index = $i;
        }
    }

    if ($index === null) {
        // Невідомий id: нічого не створюємо, просто показуємо помилку
        $notFound = true;
        http_response_code(404);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            // Змінюємо лише поля знайденої книги, id залишається той самий
            $books[$index]['title'] = $title;
            $books[$index]['author'] = $author;
            $books[$index]['year'] = $yearNumber;
            saveBooks($books);
            header('Location: index.php', true, 303);
            exit;
        }
    } else {
        // GET: лише відкриваємо форму з поточними значеннями
        $title = $books[$index]['title'];
        $author = $books[$index]['author'];
        $year = (string) $books[$index]['year'];
    }
} catch (RuntimeException $exception) {
    $errors[] = $exception->getMessage();
}
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагувати книгу (JSON)</title>
</head>
<body>
<h1>Редагування книги (JSON)</h1>
<?php if ($notFound): ?>
    <p role="alert">Книгу з таким id не знайдено.</p>
<?php else: ?>
    <?php foreach ($errors as $error): ?>
        <p role="alert"><?= e($error) ?></p>
    <?php endforeach; ?>
    <form method="post" action="edit.php?id=<?= e(rawurlencode($id)) ?>">
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
        <button type="submit">Зберегти зміни</button>
    </form>
<?php endif; ?>
<p><a href="index.php">До каталогу</a></p>
</body>
</html>
