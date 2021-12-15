<?php
require_once("App/Config/Config.php");
require_once("App/Src/vendor/autoload.php");
date_default_timezone_set("America/Sao_Paulo");
error_reporting(E_ALL);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', 1);
session_start();
header('Content-Type: application/json; charset=utf-8');

$r = getallheaders();

if (isset($r['Usuario'])) {

    if ($r['Usuario'] == 'Paralegalweb3221') {
        $dispach = new App\Controller\Dispacht();
    } else {
        $f = [
            "error" => "Não Autorizado!",
            "statusCode" => "400",
        ];
        print(json_encode($f, JSON_PRETTY_PRINT));
        http_response_code(400);
    }
} else {
    $f = [
        "error" => "Token não informado",
        "statusCode" => "400",
    ];
    print(json_encode($f, JSON_PRETTY_PRINT));
    http_response_code(400);
}
