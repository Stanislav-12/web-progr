<?php
require 'db.php';

$error = '';
$login = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    // Шукаємо користувача за логіном
    $sql = "SELECT * FROM users WHERE login = :login";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':login', $login);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Порівнюємо введений пароль із хешем з бази
    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['login'] = $user['login'];
        $_SESSION['fullname'] = $user['fullname'];
        header('Location: index.php');
        exit();
    } else {
        $error = 'Неправильний логін або пароль.';
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Вхід</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require 'menu.php'; ?>
<h1>Вхід</h1>
<?php if (isset($_GET['registered'])): ?>
    <p>Реєстрація успішна. Тепер увійдіть.</p>
<?php endif; ?>
<?php if ($error !== ''): ?>
    <p><?= e($error) ?></p>
<?php endif; ?>
<form method="post" action="login.php">
    <label for="login">Логін:</label>
    <input type="text" id="login" name="login" required value="<?= e($login) ?>"><br><br>

    <label for="password">Пароль:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Увійти</button>
</form>
</body>
</html>
