<?php

define('cc', 'GFY');
define('SUCCESS', true);
define('FAILED', false);
define('TIME_S', time());
define('ROOT', __DIR__.'/');
define('CONF_PATH', ROOT.'config.ini');
define('CLASS_PATH',ROOT.'lib/');
define('CH_PATH',ROOT.'ch/');
include ROOT.'b.php';

$app = new App();
$app->Init();
