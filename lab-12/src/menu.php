<nav>
    <a href="index.php">Каталог книг</a>
    <?php if (isset($_SESSION['user_id'])): ?>
          <a href="users.php">Користувачі</a>
          <span>Вітаємо, <?= e($_SESSION['fullname']) ?>!</span>
        <a href="logout.php">Вийти</a>
    <?php else: ?>
          <a href="login.php">Увійти</a>
          <a href="register.php">Реєстрація</a>
    <?php endif; ?>
</nav>
<hr>
