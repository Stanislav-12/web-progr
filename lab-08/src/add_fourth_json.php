<?php
declare(strict_types=1);
require __DIR__ . '/common.php';

// Додає четвертий запис до persons.json через масив
if (PHP_SAPI !== 'cli') {
    http_response_code(405);
    exit('Запустіть: php add_fourth_json.php');
}
try {
    $json = readText(__DIR__ . '/persons.json');
    $decoded = json_decode($json, false, 512, JSON_THROW_ON_ERROR);
    if (!is_array($decoded)) {
        throw new RuntimeException('Кореневе значення JSON має бути масивом.');
    }
    $people = validatePeople(json_decode($json, true, 512, JSON_THROW_ON_ERROR));

    $people[] = ['name' => 'Станіслав', 'age' => 19];

    $json = json_encode(validatePeople($people),
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    $content = $json . "\n";
    $bytes = file_put_contents(__DIR__ . '/persons.json', $content, LOCK_EX);
    if ($bytes === false || $bytes !== strlen($content)) {
        throw new RuntimeException('Не вдалося повністю записати JSON.');
    }
    echo "persons.json: записано $bytes байтів.\n";
} catch (JsonException $exception) {
    fwrite(STDERR, "Не вдалося закодувати JSON.\n");
    exit(1);
} catch (RuntimeException $exception) {
    fwrite(STDERR, $exception->getMessage() . "\n");
    exit(1);
}
