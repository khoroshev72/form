<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/inc/data.php';
require_once __DIR__ . '/inc/functions.php';


if (!empty($_POST)){
    $data = load($fields);
    if ($errors = validate($data)){
        $res = ['result' => 'error', 'errors' => $errors];
    } else {
            $mail = new PHPMailer(true);
            $flag = true;
            $message = '';
            foreach ($data as $k => $v){
                if (isset($data[$k]['mailable']) && $data[$k]['mailable'] === false){
                    continue;
                }
                $message .= ($flag = !$flag) ? '<tr>' : '<tr style="background:#E9ECEF;">';
                $message .= '<td>' . $data[$k]['field_name'] . '</td><td>' . $data[$k]['value'] . '</td></tr>';
            }
            $message = '<table style="width:100%;">' . $message . '</table>';
            $subject = "Письмо от пользователя {$data['name']['value']}";
            try {
                //Server settings
                $mail->SMTPDebug = 0;                      // Enable verbose debug output
                $mail->CharSet = 'UTF-8';
                $mail->isSMTP();                                            // Send using SMTP
                $mail->Host       = $mail_settings['host'];                    // Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                $mail->Username   = $mail_settings['username'];                     // SMTP username
                $mail->Password   = $mail_settings['password'];                               // SMTP password
                $mail->SMTPSecure = $mail_settings['secure'];         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $mail->Port       = $mail_settings['port'];                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
                //Recipients
                $mail->setFrom($mail_settings['from_mail'], $mail_settings['from_app']);
                $mail->addAddress($mail_settings['from_mail']);
                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = $subject;
                $mail->Body    = $message;

                if ($mail->send()) {
                    $res = ['result' => 'ok', 'captcha' => set_captcha()];
                    exit(json_encode($res));
                }

            } catch (Exception $e) {
                if (!$mail->SMTPDebug){
                    $res = ['result' => 'fail', 'error' => 'Ошибка при отправке письма'];
                    exit(json_encode($res));
                }
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
    }
}
?>


<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Обработка формы</title>
</head>
<body>
<div class="container">
    <div class="responce"></div>
    <div class="row">
        <div class="col-md-6 offset-md-3 mt-md-5">
            <form method="POST" id="form" class="needs-validation" novalidate>
                <div class="form-group">
                    <label for="name">Имя</label>
                    <input type="text" name="name" class="form-control" id="name" required>
                    <div class="invalid-feedback">
                        Пожалуйста, введите поле 'Имя'
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" id="email" required>
                    <div class="invalid-feedback">
                        Пожалуйста, введите поле 'Email'
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone">Телефон</label>
                    <input type="text" name="phone" class="form-control" id="phone" required>
                    <div class="invalid-feedback">
                        Пожалуйста, введите поле 'Телефон'
                    </div>
                </div>

                <div class="form-group">
                    <label for="message">Соббщение</label>
                    <textarea name="message" id="message" class="form-control" rows="3" autofocus></textarea>
                </div>

                <div class="form-group">
                    <label id="label-captcha" for="captcha"><?=set_captcha() ?></label>
                    <input type="text" name="captcha" class="form-control" id="captcha" required>
                    <div class="invalid-feedback">
                        Пожалуйста, введите поле 'Капча'
                    </div>
                </div>


                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="agreed" id="agreed" checked required>
                    <label class="form-check-label" for="agreed">Согласен на обработку персональных данных</label>
                </div>

                <button type="submit" class="btn btn-primary">Отправить</button>

                <div class="loader">
                    <img src="loader.svg">
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="main.js"></script>
</body>
</html>