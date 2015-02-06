<?php

$debug = true;
$filename = 'ep19.txt';

function isBlank($field) {
    return (!$field || $field == "" || $field == "null");
}

function logtxt($text) {
    global $debug;
    if ($debug == true) {
        file_put_contents('php://stderr', "$text\n");
    }
}

function logvar($name,$var) {
    global $debug;
    if ($debug == true) {
        $var_value = print_r($var, TRUE);
        file_put_contents('php://stderr', "$name=$var_value\n");
    }
}

function get_post_data() {
    logvar('POSTDATA', $_POST);
    $result = array();
    foreach ($_POST as $key => $value) {
        $result[$key] = htmlspecialchars($value);
        if (strcasecmp($result[$key], "undefined") == 0) {
            $result[$key] = "";
        }
    }
    return $result;
}

function get_missing_values($argArray) {
    logvar("get_missing_values argument array", $argArray);
    $optionals = array("remarks","cars","bikes");
    $result = array();

    foreach($argArray as $key => $value) {
        if (!in_array($key, $optionals) && isBlank($value)) {
            $result[] = $key;
        }
    }
    return $result;
}

function get_non_numerics($argarray) {
    $result = array();
    if (!is_numeric($argarray['persons'])) {
        array_unshift($result, "persons");
    }
    if ($argarray['bikes'] != "" && !is_numeric($argarray['bikes'])) {
        array_unshift($result, "bikes");
    }
    if ($argarray['cars'] != "" && !is_numeric($argarray['cars'])) {
        array_unshift($result, "cars");
    }
    return $result;
}

function write_to_file($data) {
    global $filename;
    $csvfields = array($data['naam'], $data['persons'], $data['bikes'], $data['cars'],
        $data['room'], $data['remarks'], $data['arrival'], $data['depart']);

    if (!file_exists($filename)) {
        touch($filename);
    }

    $handle = fopen($filename, 'a+');
    $writeresult = fputcsv($handle, $csvfields);
    fclose($handle);
    return $writeresult;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postdata = get_post_data();
    $missing_fields = get_missing_values($postdata);
    $not_numeric = get_non_numerics($postdata);

    $writeresult = false;
    if (count($missing_fields) == 0 && count($not_numeric) == 0) {
        $writeresult = write_to_file($postdata);
    }

    header('Content-Type: application/javascript');
    echo('{ "missing": ' . json_encode($missing_fields) . ', "notnum": ' . json_encode($not_numeric) . ', "written": ' . json_encode($writeresult) . ' }');
    
}