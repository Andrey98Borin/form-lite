<?php
require_once 'vendor/autoload.php';
include 'db.php';

use YooKassa\Model\Notification\NotificationSucceeded;
use YooKassa\Model\NotificationEventType;

$source = file_get_contents('php://input');
$requestBody = json_decode($source, true);

try {
    $notification = new NotificationSucceeded($requestBody);
    $payment = $notification->getObject();

    if ($notification->getEvent() === NotificationEventType::PAYMENT_SUCCEEDED) {
        $orderId = $payment->metadata->order_id;
        $stmt = $pdo->prepare("UPDATE payments SET status = 'paid' WHERE id = ?");
        $stmt->execute([$orderId]);
    }
} catch (Exception $e) {
    // Логирование ошибки
    file_put_contents('yookassa_webhook.log', date('Y-m-d H:i:s') . ' - ' . $e->getMessage() . "\n", FILE_APPEND);
}