<?php

function isBlank($field) {
    return (!$field || $field == "");
}

$method = $_SERVER['REQUEST_METHOD'];
$ok = true;

function get_post_data() {
    file_put_contents('php://stderr', print_r($_POST, TRUE));
    $result = [];
    foreach ($_POST as $key => $value) {
        var_dump($key,$value);
        $result[$key] = htmlentities($value);
    }
    var_dump($result);
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
    foreach ($argarray as $key => $value) {
        if (!is_numeric($value)) {
            $result[] = $key;
        }
    }
    return $result;
}

function write_to_file($data) {
    var_dump($data);
    $fname = 'ep20.txt';
    $csvfields = array($data['naam'], $data['persons'], $data['room'], $data['remarks']);

    if (!file_exists($fname)) {
        touch($fname);
    }

    $handle = fopen($fname, 'a+');
    fputcsv($handle, $csvfields);
}

if ($method == 'POST') {
    $postdata = get_post_data();
    var_dump($postdata);
    $missing_fields = get_missing_values($postdata);
    $not_numeric = get_non_numerics($postdata);

    if (count($missing_fields) == 0 && count($not_numeric) == 0) {
        write_to_file($postdata);
    }
    
    echo("{ missing : " +json_encode($missing_fields) + ", notnum : " + json_encode($not_numeric) + " }");
    
}