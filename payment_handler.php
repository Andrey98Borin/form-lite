<?php
require_once 'vendor/autoload.php';
include 'db.php';

use YooKassa\Client;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $amount = (float)$_POST['amount'];

    try {
        // Сохраняем заказ в БД
        $stmt = $pdo->prepare("INSERT INTO payments (name, email, phone, amount, status) VALUES (?, ?, ?, ?, 'pending')");
        $stmt->execute([$name, $email, $phone, $amount]);
        $paymentId = $pdo->lastInsertId();

        // Инициализируем клиент ЮKassa
        $client = new Client();
        $client->setAuth('497164', 'test_H3UD5k21py12lyXB1bKCQV_etJiPNqTqAldV4Y92lzQ'); // Замените на реальные данные!

        // Формируем данные для платежа
        $paymentData = [
            'amount' => [
                'value' => $amount,
                'currency' => 'RUB',
            ],
            'confirmation' => [
                'type' => 'redirect',
                'return_url' => 'http://ваш-сайт/success.php?id='.$paymentId,
            ],
            'description' => 'Оплата заказа №'.$paymentId,
            'metadata' => [
                'order_id' => $paymentId,
            ],
            'receipt' => [
                'customer' => [
                    'email' => $email, // Обязательное поле для электронного чека
                ],
                'items' => [
                    [
                        'description' => 'Оплата услуг', // Название товара/услуги
                        'quantity' => '1.00',
                        'amount' => [
                            'value' => $amount,
                            'currency' => 'RUB',
                        ],
                        'vat_code' => 1, // НДС 20%
                        'payment_mode' => 'full_payment',
                        'payment_subject' => 'service',
                    ]
                ]
            ]
        ];

        // Создаем платеж
        $payment = $client->createPayment($paymentData, uniqid('', true));

        // Перенаправляем на страницу оплаты
        header('Location: '.$payment->getConfirmation()->getConfirmationUrl());
        exit;

    } catch (Exception $e) {
        // Логируем ошибку
        file_put_contents('payment_errors.log', date('Y-m-d H:i:s').' - '.$e->getMessage()."\n", FILE_APPEND);
        die('Произошла ошибка при обработке платежа. Пожалуйста, попробуйте позже.');
    }
}
?>