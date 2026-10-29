<?php
declare(strict_types=1);
require __DIR__ . '/common.php';

// Приклад обходу DOM із теорії
header('Content-Type: text/html; charset=UTF-8');
$items = [];
$error = null;
try {
    $dom = parseXml(readText(__DIR__ . '/persons.xml'));
    peopleFromXml($dom); // Перевірка структури перед обходом.
    foreach ($dom->getElementsByTagName('person') as $person) {
        $name = $person->getElementsByTagName('name')->item(0);
        $age = $person->getElementsByTagName('age')->item(0);
        $items[] = $name->textContent . ' - ' . $age->textContent . ' р';
    }
} catch (RuntimeException $exception) {
    http_response_code(500);
    $error = $exception->getMessage();
}
?>
<!DOCTYPE html>
<html lang="uk">
<head><meta charset="utf-8"><title>Обхід DOM</title></head>
<body>
<h1>Обхід DOM: перелік записів</h1>
<?php if ($error !== null): ?>
    <p><?= e($error) ?></p>
<?php else: ?>
    <ul>
        <?php foreach ($items as $item): ?>
            <li><?= e($item) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
</body>
</html>
