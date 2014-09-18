<?php
//Path setting
$pjroot = $_SERVER['DOCUMENT_ROOT'] . "/kintai/";
$path = $pjroot . "config" . PATH_SEPARATOR;
$path .= $pjroot . "model" . PATH_SEPARATOR;
$path .= $pjroot . "include" . PATH_SEPARATOR;
$path .= $pjroot . "lib" . PATH_SEPARATOR;
$path .= $pjroot . "controller". PATH_SEPARATOR;
// $path .="/usr/share/pear/";
// $path = str_replace("/", "\\", $path);

ini_set("include_path", $path);

?>