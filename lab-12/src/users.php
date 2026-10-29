<?php
require 'db.php';

// Сторінка лише для авторизованих
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Список користувачів, пароль не вибираємо
$sql = "SELECT id, login, fullname, email FROM users ORDER BY id";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Користувачі</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require 'menu.php'; ?>
<h1>Список користувачів</h1>
<?php if (count($users) > 0): ?>
    <table>
        <tr><th>ID</th><th>Логін</th><th>ПІБ</th><th>Email</th></tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= e($user['login']) ?></td>
                <td><?= e($user['fullname']) ?></td>
                <td><?= e($user['email']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>У базі даних немає користувачів.</p>
<?php endif; ?>
</body>
</html>
