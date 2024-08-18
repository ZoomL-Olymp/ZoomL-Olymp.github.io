<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 

  // Валидация reCAPTCHA
  $secretKey = '6LcuZSkqAAAAAGBfTsTPPddh9kD8EJJ9-3JBLuBN';
  $response = $_POST['g-recaptcha-response'];

  // Проверка, что ответ reCAPTCHA не пустой
  if (empty($response)) {
    die("Ошибка: Пожалуйста, подтвердите, что вы не робот.");
  }

  $remoteip = $_SERVER['REMOTE_ADDR'];
  $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$response&remoteip=$remoteip";
  $recaptcha = json_decode(file_get_contents($url), true);

  if ($recaptcha['success'] == true) {

    // reCAPTCHA пройдена, обрабатываем данные формы

    // Получаем данные из формы с использованием filter_input() для безопасности
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $organization = filter_input(INPUT_POST, 'organization', FILTER_SANITIZE_STRING);
    $inn = filter_input(INPUT_POST, 'inn', FILTER_SANITIZE_STRING);

    // Дополнительная валидация данных (например, проверка формата телефона)
    // ...

    $to = 'sale@consultib.ru';
    $subject = 'Новая заявка с сайта ConsultIB';

    // Формируем тело письма с использованием htmlspecialchars() для предотвращения XSS атак
    $message = "
      <html>
      <head>
        <title>Новая заявка</title>
      </head>
      <body>
        <h2>Новая заявка с сайта ConsultIB</h2>
        <p><strong>ФИО:</strong> " . htmlspecialchars($name) . "</p>
        <p><strong>Телефон:</strong> " . htmlspecialchars($phone) . "</p>
        <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
        <p><strong>Организация:</strong> " . htmlspecialchars($organization) . "</p>
        <p><strong>ИНН:</strong> " . htmlspecialchars($inn) . "</p>
      </body>
      </html>
    ";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <noreply@consultib.ru>' . "\r\n";

    // Отправляем письмо
    if (mail($to, $subject, $message, $headers)) {
      echo "Письмо успешно отправлено!";
    } else {
      echo "Ошибка отправки письма.";
    }

  } else {
    die("Ошибка: Пожалуйста, подтвердите, что вы не робот.");
  }

} else {
  // Метод запроса не POST, ничего не делаем
}

?>