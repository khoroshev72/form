<?php

function debug($data, $die = false){
    echo '<pre>' . print_r($data, 1) . '</pre>';
    if ($die) die;
}

function h($data){
    return htmlspecialchars($data, ENT_QUOTES);
}

function load($data){
    foreach ($_POST as $k => $v){
        if (array_key_exists($k, $data)){
            $data[$k]['value'] = trim($v);
        }
    }
    return $data;
}

function validate($data){
    $errors = '';
    foreach ($data as $k => $v){
        if ($data[$k]['required'] && empty($data[$k]['value'])){
            $errors .= "<li class='list-group-item list-group-item-danger'>Поле {$data[$k]['field_name']} должно быть заполнено</li>";
        }
    }
    if (!get_captcha($data['captcha']['value'])){
        $errors .= "<li class='list-group-item list-group-item-danger'>Неверно указано поле " . h($data['captcha']['field_name']) . "</li>";
    }
    if ($errors){
        $errors = "<ul class='list-group'>$errors</ul>";
        return $errors;
    }
    return false;
}

function set_captcha(){
    $num1 = rand(1,100);
    $num2 = rand(1,100);
    $_SESSION['captcha'] = $num1 + $num2;
    return "Введите сумму $num1 + $num2";
}

function get_captcha($res){
    return trim($res) == $_SESSION['captcha'];
}