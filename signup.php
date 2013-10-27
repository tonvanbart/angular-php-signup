<?php

$debug = true;
$filename = 'ep20.txt';

function isBlank($field) {
    return (!$field || $field == "");
}

function debug($name, $value) {
    global $debug;
    if ($debug == true) {
        $value_to_print = "";
        if ( is_array( $value ) )  {
            $value_to_print = print_r ( $value, TRUE );
        } else {
            $value_to_print = var_dump ( $value, TRUE );
        }
        echo("$name:$value_to_print\n");
    }
}

function logtxt($text) {
    file_put_contents('php://stderr', "$text\n");
}

function logvar($name,$var) {
    $var_value = print_r($var, TRUE);
    file_put_contents('php://stderr', "$name=$var_value\n");
}

function get_post_data() {
    $result = [];
    foreach ($_POST as $key => $value) {
        $result[$key] = htmlentities($value);
    }
    return $result;
}

function get_missing_values($argArray) {
    $result = [];

//    foreach(array("naam","persons","room") as $key) {
//        if (!array_key_exists($key, $argArray)) {
//            $result[] = $key;
//        }
//    }

    foreach($argArray as $key => $value) {
        if ($key != "remarks" && isBlank($value)) {
            $result[] = $key;
        }
    }
    return $result;
}

function get_non_numerics($argarray) {
    $result = [];
    if (!is_numeric($argarray['persons'])) {
        $result[] = 'persons';
    }
    return $result;
}

function write_to_file($data) {
    global $filename;
    $csvfields = array($data['naam'], $data['persons'], $data['room'], $data['remarks']);

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

    if (count($missing_fields) == 0 && count($not_numeric) == 0) {
        $writeresult = write_to_file($postdata);
    }
    
    echo("{ missing: " . json_encode($missing_fields) . ", notnum: " . json_encode($not_numeric) . ", written: " . $writeresult . " }");
    
}