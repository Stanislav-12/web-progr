<?php
declare(strict_types=1);
// Беремо готові e(), readText, parseXml з прикладу
require __DIR__ . '/../src/common.php';

session_start();
$_SESSION['csrf'] ??= bin2hex(random_bytes(32));

// Шлях до файла задає програма, а не користувач
const BOOKS_FILE = __DIR__ . '/book.xml';

function checkCsrf(): void
{
    $token = $_POST['csrf'] ?? null;
    if (!is_string($token) || !hash_equals($_SESSION['csrf'], $token)) {
        throw new RuntimeException('Форма застаріла. Оновіть сторінку.');
    }
}

// Значення з POST, масив або відсутнє поле стає порожнім рядком
function postString(string $key): string
{
    $value = $_POST[$key] ?? '';
    return is_string($value) ? trim($value) : '';
}

// Перевірка списку книг
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

// Читання книг із DOM
function booksFromXml(DOMDocument $dom): array
{
    $root = $dom->documentElement;
    if ($root === null || $root->tagName !== 'library' || $root->attributes->length !== 0) {
        throw new RuntimeException('Очікується кореневий елемент library без атрибутів.');
    }
    $books = [];
    foreach ($root->childNodes as $node) {
        if ($node instanceof DOMText && trim($node->textContent) === '') {
            continue;
        }
        if (!$node instanceof DOMElement || $node->tagName !== 'book'
            || $node->attributes->length !== 0) {
            throw new RuntimeException('Очікується елемент book без атрибутів.');
        }
        $fields = [];
        foreach ($node->childNodes as $field) {
            if ($field instanceof DOMText && trim($field->textContent) === '') {
                continue;
            }
            if (!$field instanceof DOMElement
                || !in_array($field->tagName, ['id', 'title', 'author', 'year'], true)
                || array_key_exists($field->tagName, $fields)
                || $field->attributes->length !== 0) {
                throw new RuntimeException('Поля book мають бути id, title, author, year без повторів.');
            }
            foreach ($field->childNodes as $part) {
                if (!$part instanceof DOMText && !$part instanceof DOMCdataSection) {
                    throw new RuntimeException('Поле має містити лише текст.');
                }
            }
            $fields[$field->tagName] = $field->textContent;
        }
        // У XML рік - текст, тому перетворюємо його на число
        $rawYear = $fields['year'] ?? '';
        $year = ctype_digit($rawYear)
            ? filter_var($rawYear, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1450, 'max_range' => (int) date('Y')]])
            : false;
        if ($year === false) {
            throw new RuntimeException('Рік має бути цілим числом 1450–' . date('Y') . '.');
        }
        $fields['year'] = $year;
        $books[] = $fields;
    }
    return validateBooks($books);
}

// Створення DOM зі списку книг
function booksToXml(array $books): DOMDocument
{
    $books = validateBooks($books);
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->formatOutput = true;
    $root = $dom->createElement('library');
    $dom->appendChild($root);
    foreach ($books as $book) {
        $node = $dom->createElement('book');
        foreach (['id', 'title', 'author', 'year'] as $key) {
            $field = $dom->createElement($key);
            $field->appendChild($dom->createTextNode((string) $book[$key]));
            $node->appendChild($field);
        }
        $root->appendChild($node);
    }
    return $dom;
}

function loadBooks(): array
{
    return booksFromXml(parseXml(readText(BOOKS_FILE)));
}

function saveBooks(array $books): void
{
    if (!is_writable(BOOKS_FILE)) {
        throw new RuntimeException('Файл каталогу недоступний для запису.');
    }
    $bytes = booksToXml($books)->save(BOOKS_FILE);
    if ($bytes === false) {
        throw new RuntimeException('Не вдалося зберегти XML.');
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
