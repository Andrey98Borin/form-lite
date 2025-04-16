<?php
include 'db.php';

// Проверяем статус платежа (можно добавить проверку через API ЮKassa)
$orderId = $_GET['orderId'] ?? null;
if ($orderId) {
    $stmt = $pdo->prepare("SELECT status FROM payments WHERE id = ?");
    $stmt->execute([$orderId]);
    $status = $stmt->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Успешная оплата</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <?php if ($status === 'paid'): ?>
        <div class="alert alert-success">Оплата прошла успешно!</div>
    <?php else: ?>
        <div class="alert alert-warning">Платеж в обработке...</div>
    <?php endif; ?>
</div>
</body>
</html>