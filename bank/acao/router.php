<?php

require_once '../../vendor/autoload.php';

switch ($_GET['route'] ?? $route) {
    case "cadastrar":
        (new \App\AccountController)->createUser();
        break;

    case "reset":
        (new \App\AccountController)->resetUsers();
        break;

    case "find":
        (new \App\AccountController)->find();
        break;

    case "getnews":
        (new \App\AccountController)->getNews();
        break;
}