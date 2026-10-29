<?php
declare(strict_types=1);
require __DIR__ . '/common.php';

// Додає четвертий запис до persons.xml через дерево DOM
if (PHP_SAPI !== 'cli') {
    http_response_code(405);
    exit('Запустіть: php add_fourth_xml.php');
}
try {
    $dom = parseXml(readText(__DIR__ . '/persons.xml'));
    peopleFromXml($dom); // Спочатку перевіряємо наявний файл

    $person = $dom->createElement('person');
    foreach (['name' => 'Станіслав', 'age' => '19'] as $key => $value) {
        $field = $dom->createElement($key);
        $field->appendChild($dom->createTextNode($value));
        $person->appendChild($field);
    }
    $dom->documentElement->appendChild($person);

    $dom->formatOutput = true;
    $bytes = $dom->save(__DIR__ . '/persons.xml');
    if ($bytes === false) {
        throw new RuntimeException('Не вдалося зберегти XML.');
    }
    echo "persons.xml: записано $bytes байтів.\n";
} catch (RuntimeException $exception) {
    fwrite(STDERR, $exception->getMessage() . "\n");
    exit(1);
}
