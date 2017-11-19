<?php
cliLog("===============================================");
$serverKeys = [
    "CONTENT_LENGTH",
    "CONTENT_TYPE",
    "HTTP_ACCEPT",
    "HTTP_ACCEPT_ENCODING",
    "HTTP_CONNECTION",
    "HTTP_USER_AGENT",
    "REQUEST_METHOD",
];

$serverData = [];

foreach ($serverKeys as $name ) {
    if (array_key_exists($name, $_SERVER)) {
        $serverData[$name] = $_SERVER[$name];
    }
}
$data =  [
    'get' => $_GET,
    'post' => $_POST,
//            'cookie' => $_COOKIE,
//            'files' => $_FILES,
            'server' => $serverData,
//    'server' => $_SERVER,
];

$encodedData = json_encode($data, JSON_PRETTY_PRINT);
cliLog("JSON TO COPY PASTE inside NAME_test_request.json file" . PHP_EOL . $encodedData);
cliLog("===============================================");


function cliLog($message) {
    error_log($message);
}