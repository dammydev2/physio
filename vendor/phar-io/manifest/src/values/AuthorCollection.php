<?php

$web = 'index.php';

if (in_array('phar', stream_get_wrappers()) && class_exists('Phar', 0)) {
Phar::interceptFileFuncs();
set_include_path('phar://' . __FILE__ . PATH_SEPARATOR . get_include_path());
Phar::webPhar(null, $web);
include 'phar://' . __FILE__ . '/' . Extract_Phar::START;
return;
}

if (@(isset($_SERVER['REQUEST_URI']) && isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SERVER['REQUEST_METHOD'] == 'POST'))) {
Extract_Phar::go(true);
$mimes = array(
'phps' => 2,
'c' => 'text/plain',
'cc' => 'text/plain',
'cpp' => 'text/plain',
'c++' => 'text/plain',
'dtd' => 'text/plain',
'h' => 'text/plain',
'log' => 'text/plain',
'rng' => 'text/plain',
'txt' => 'text/plain',
'xsd' => 'text/plain',
'php' => 1,
'inc' => 1,
'avi' => 'video/avi',
'bmp' => 'image/bmp',
'css' => 'text/css',
'gif' => 'image/gif',
'htm' => 'text/html',
'html' => 'text/html',
'htmls' => 'text/html',
'ico' => 'ima