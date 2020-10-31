<?php

$fields = [
    'name' => [
        'field_name' => 'Имя',
        'required' => true,
    ],
    'email' => [
        'field_name' => 'Email',
        'required' => true,
    ],
    'phone' => [
        'field_name' => 'Телефон',
        'required' => true,
    ],
    'message' => [
        'field_name' => 'Комментарий',
        'required' => false,
    ],
    'agreed' => [
        'field_name' => 'Согласие на обработку персональных данных',
        'required' => true,
        'mailable' => false,
    ],
    'captcha' => [
        'field_name' => 'Captcha',
        'required' => true,
        'mailable' => false,
    ],
];

$mail_settings = [
    'host' => 'smtp.mailtrap.io',
    'port' => '465',
    'username' => 'a12ad2b027f5a1',
    'password' => 'afade6f4c24077',
    'secure' => null,
    'from_mail' => '14361e2825-0a9c48@inbox.mailtrap.io',
    'from_app' => 'Form Application',
];