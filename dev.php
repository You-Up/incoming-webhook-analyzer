<?php
    date_default_timezone_set('UTC');
    require_once  'vendor/autoload.php';

    $a = file_get_contents($argv[1]);

    $a = json_decode($a, true);

    $r = new \Illuminate\Http\Request(
        $a['get'],
        $a['post'],
        [],
        [],
        [],
        $a['server']
    );
    $res = WebhookParser\Main::run($r);
//    print_r($res);
    print json_encode($res, JSON_PRETTY_PRINT);
