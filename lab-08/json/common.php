<?php
declare(strict_types=1);
// Беремо готові e та readText з прикладу
require __DIR__ . '/../src/common.php';

session_start();
$_SESSION['csrf'] ??= bin2hex(random_bytes(32));

// Шлях до файла задає програма, а не користувач
const BOOKS_FILE = __DIR__ . '/book.json';

function checkCsrf(): void
{
    $token = $_POST['csrf'] ?? null;
    if (!is_string($token) || !hash_equals($_SESSION['csrf'], $token)) {
        throw new RuntimeException('Форма застаріла. Оновіть сторінку.');
    }
}

// Значення з POST: масив або відсутнє поле стає порожнім рядком
function postString(string $key): string
{
    $value = $_POST[$key] ?? '';
    return is_string($value) ? trim($value) : '';
}

// Ті самі перевірки, що й у XML-версії
function validateBooks(mixed $books): array
{
    if (!is_array($books) || !array_is_list($books)) {
        throw new RuntimeException('Очікується список книг.');
    }
    $ids = [];
    foreach ($books as $book) {
        if (!is_array($book) || count($book) !== 4
            || !isset($book['id'], $book['title'], $book['author'], $book['year'])
            || !is_string($book['id']) || !preg_match('/\A[a-f0-9]{16}\z/', $book['id'])
            || !is_string($book['title']) || trim($book['title']) === '' || mb_strlen($book['title']) > 100
            || !is_string($book['author']) || trim($book['author']) === '' || mb_strlen($book['author']) > 60
            || !is_int($book['year']) || $book['year'] < 1450 || $book['year'] > (int) date('Y')) {
            throw new RuntimeException('Книга має містити id, назву, автора та рік 1450–' . date('Y') . '.');
        }
        if (in_array($book['id'], $ids, true)) {
            throw new RuntimeException('У каталозі повторюється id.');
        }
        $ids[] = $book['id'];
    }
    return $books;
}

// Читання за зразком read_persons_json.php
function loadBooks(): array
{
    try {
        $json = readText(BOOKS_FILE);
        $decoded = json_decode($json, false, 512, JSON_THROW_ON_ERROR);
        if (!is_array($decoded)) {
            throw new RuntimeException('Кореневе значення JSON має бути масивом.');
        }
        return validateBooks(json_decode($json, true, 512, JSON_THROW_ON_ERROR));
    } catch (JsonException $exception) {
        throw new RuntimeException('Некоректний JSON або кодування UTF-8.');
    }
}

// Запис за зразком write_persons_json.php.
function saveBooks(array $books): void
{
    if (!is_writable(BOOKS_FILE)) {
        throw new RuntimeException('Файл каталогу недоступний для запису.');
    }
    try {
        $json = json_encode(validateBooks($books),
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    } catch (JsonException $exception) {
        throw new RuntimeException('Не вдалося закодувати JSON.');
    }
    $content = $json . "\n";
    $bytes = file_put_contents(BOOKS_FILE, $content, LOCK_EX);
    if ($bytes === false || $bytes !== strlen($content)) {
        throw new RuntimeException('Не вдалося повністю записати JSON.');
    }
}

// Новий id, якого ще немає в каталозі
function newId(array $books): string
{
    $ids = array_column($books, 'id');
    do {
        $id = bin2hex(random_bytes(8));
    } while (in_array($id, $ids, true));
    return $id;
}
