<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "siddha_art";

global $connect;
$connect = mysqli_connect($servername, $username, $password, $database);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Helper functions for double Base64 encoding & decoding of IDs
if (!function_exists('encodeId')) {
    function encodeId($id) {
        if (empty($id)) return '';
        return urlencode(base64_encode(base64_encode((string)$id)));
    }
}

if (!function_exists('decodeId')) {
    function decodeId($encodedId) {
        if (empty($encodedId)) return 0;
        $str = trim((string)$encodedId);
        if (ctype_digit($str)) {
            return intval($str);
        }
        $once = base64_decode(urldecode($str), true);
        if ($once !== false) {
            $twice = base64_decode($once, true);
            if ($twice !== false && is_numeric($twice)) {
                return intval($twice);
            }
        }
        return is_numeric($str) ? intval($str) : 0;
    }
}
?>
