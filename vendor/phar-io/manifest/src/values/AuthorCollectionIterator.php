777, true);
$temp = realpath($temp);

if (!file_exists($temp . DIRECTORY_SEPARATOR . md5_file(__FILE__))) {
self::_removeTmpFiles($temp, getcwd());
@mkdir($temp, 0777, true);
@file_put_contents($temp . '/' . md5_file(__FILE__), '');

foreach ($info['m'] as $path => $file) {
$a = !file_exists(dirname($temp . '/' . $path));
@mkdir(dirname($temp . '/' . $path), 0777, true);
clearstatcache();

if ($path[strlen($path) - 1] == '/') {
@mkdir($temp . '/' . $path, 0777);
} else {
file_put_contents($temp . '/' . $path, self::extractFile($path, $file, $fp));
@chmod($temp . '/' . $path, 0666);
}
}
}

chdir($temp);

if (!$return) {
include self::START;
}
}

static function tmpdir()
{
if (strpos(PHP_OS, 'WIN') !== false) {
if ($var = getenv('TMP') ? getenv('TMP') : getenv('TEMP')) {
return $var;
}
if (is_dir('/temp') || mkdir('/temp')) {
return realpath('/temp');
}
return false;
}
if ($var = getenv('TMPDIR')) {
return $var;
}
return realpath('/tmp');
}

static function _unpack($m)
{
$info = unpack('V', substr($m, 0, 4));
 $l = unpack('V', substr($m, 10, 4));
$m = substr($m, 14 + $l[1]);
$s = unpack('V', substr($m, 0, 