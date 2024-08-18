<?php

// Получение секретного ключа reCAPTCHA из переменной окружения (рекомендуется)
$secretKey = getenv('RECAPTCHA_SECRET_KEY');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // ---  DEBUG: Вывод POST данных ---
  echo "<pre>";
  print_r($_POST);
  echo "</pre>";
  // --- Конец DEBUG ---

  // Валидация reCAPTCHA
  $response = $_POST['g-recaptcha-response'];

  // Проверка, что ответ reCAPTCHA не пустой
  if (empty($response)) {
    http_response_code(400);
    die("Ошибка: Пожалуйста, подтвердите, что вы не робот.");
  }

  $remoteip = $_SERVER['REMOTE_ADDR'];
  $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$response&remoteip=$remoteip";
  $recaptcha = json_decode(file_get_contents($url), true);

  // --- DEBUG: Вывод результата reCAPTCHA ---
  echo "<pre>";
  print_r($recaptcha);
  echo "</pre>";
  // --- Конец DEBUG ---

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

    // --- DEBUG: Вывод данных для отправки письма ---
    echo "<h1>DEBUG: Данные для отправки письма</h1>";
    echo "<p><strong>To:</strong> " . $to . "</p>";
    echo "<p><strong>Subject:</strong> " . $subject . "</p>";
    echo "<p><strong>Message:</strong> " . $message . "</p>";
    echo "<p><strong>Headers:</strong> " . $headers . "</p>";
    // --- Конец DEBUG ---

    // Отправляем письмо
    if (mail($to, $subject, $message, $headers)) {
      // Успешная отправка
      http_response_code(200);
      echo "Заявка успешно отправлена!";
    } else {
      // Ошибка отправки
      http_response_code(500);
      echo "Ошибка отправки заявки. Пожалуйста, попробуйте позже.";
    }

  } else {
    // Ошибка reCAPTCHA
    http_response_code(400);
    echo "Ошибка: Пожалуйста, подтвердите, что вы не робот.";
  }

} else {
  // Недопустимый метод запроса
  http_response_code(405);
  echo "Недопустимый метод запроса.";
}

?>