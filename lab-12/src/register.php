<?php
require 'db.php';

$errors = [];
$login = '';
$fullname = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Отримати дані з форми, прибрати зайві пробіли
    $login = trim($_POST['login'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Прості перевірки полів
    if ($login === '' || mb_strlen($login) > 50) {
        $errors[] = 'Логін: від 1 до 50 символів.';
    }
    if ($fullname === '' || mb_strlen($fullname) > 100) {
        $errors[] = 'ПІБ: від 1 до 100 символів.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Введіть правильний email.';
    }
    if (mb_strlen($password) < 6) {
        $errors[] = 'Пароль: щонайменше 6 символів.';
    }

    // Перевірка, що такого логіну або email ще немає
    if (count($errors) === 0) {
        $sql = "SELECT id FROM users WHERE login = :login OR email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->fetch()) {
            $errors[] = 'Користувач з таким логіном або email вже існує.';
        }
    }

    // Додавання користувача, пароль зберігаємо як bcrypt хеш
    if (count($errors) === 0) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (login, fullname, email, password) VALUES (:login, :fullname, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hash);
        $stmt->execute();

        header('Location: login.php?registered=1');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Реєстрація</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require 'menu.php'; ?>
<h1>Реєстрація</h1>
<?php foreach ($errors as $error): ?>
    <p><?= e($error) ?></p>
<?php endforeach; ?>
<form method="post" action="register.php">
    <label for="login">Логін:</label>
    <input type="text" id="login" name="login" maxlength="50" required value="<?= e($login) ?>"><br><br>

    <label for="fullname">ПІБ:</label>
    <input type="text" id="fullname" name="fullname" maxlength="100" required value="<?= e($fullname) ?>"><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" maxlength="100" required value="<?= e($email) ?>"><br><br>

    <label for="password">Пароль:</label>
    <input type="password" id="password" name="password" minlength="6" required><br><br>

    <button type="submit">Зареєструватися</button>
</form>
</body>
</html>
