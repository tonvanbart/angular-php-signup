<?php

function unescape($arg) {
    if (get_magic_quotes_gpc()) {
        return stripslashes($arg);
    }
    return $arg;
}

$filename = "ep19.txt";
file_put_contents('php://stderr', "list stored participants\n");

$handle = fopen($filename, 'r');
$result = array();
header('Content-Type: application/javascript; charset=UTF-8');
header('Pragma: no-cache');
header('Cache-Control: no-cache, no-store');

while (($data = fgetcsv($handle)) !== false) {
    // layout is: naam, persons, bikes, cars, room, remarks, arrival, depart
    $person = array(
        "naam"    => unescape($data[0]),
        "persons" => unescape($data[1]),
        "bikes"   => unescape($data[2]),
        "cars"    => unescape($data[3]),
        "room"    => unescape($data[4]),
        "remarks" => unescape($data[5]),
        "arrival" => unescape($data[6]),
        "depart"  => unescape($data[7])
    );
    array_unshift($result, $person);
}
echo(json_encode($result));
fclose($handle);