<?php

echo 'PHP version: ' . PHP_VERSION . '<br>';
echo 'Loaded extensions has fileinfo: ' . (extension_loaded('fileinfo') ? 'YES' : 'NO') . '<br>';
echo 'class_exists("finfo"): ' . (class_exists('finfo') ? 'YES' : 'NO') . '<br>';
echo 'function_exists("finfo_open"): ' . (function_exists('finfo_open') ? 'YES' : 'NO') . '<br>';

if (class_exists('finfo')) {
    $f = new finfo(FILEINFO_MIME_TYPE);
    echo 'finfo instantiated: YES<br>';
}