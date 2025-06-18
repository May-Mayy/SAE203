<?php

function debug($var) {
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}


function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}


function isLoggedIn() {
    return isset($_SESSION['user_id']);
}


function redirect($url) {
    header("Location: $url");
    exit();
}
