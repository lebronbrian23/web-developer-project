<?php
echo "Current PHP Limits:\n";
echo "post_max_size: " . ini_get('post_max_size') . "\n";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "\nIn bytes:\n";
echo "post_max_size: " . ini_get('post_max_size') . " = " . get_bytes(ini_get('post_max_size')) . " bytes\n";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . " = " . get_bytes(ini_get('upload_max_filesize')) . " bytes\n";

function get_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    switch($last) {
        case 'g': $val *= 1024;
        case 'm': $val *= 1024;
        case 'k': $val *= 1024;
    }
    return $val;
}
?>
