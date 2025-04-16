<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Оплата</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Форма оплаты</h2>
    <form action="payment_handler.php" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Имя</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Телефон</label>
            <input type="tel" class="form-control" id="phone" name="phone" required>
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label">Сумма (руб)</label>
            <input type="number" class="form-control" id="amount" name="amount" min="1" required>
        </div>
        <button type="submit" class="btn btn-primary">Оплатить</button>
    </form>
</div>
</body>
</html>