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
header('Content-Type: application/javascript');

while (($data = fgetcsv($handle)) !== false) {
    // layout is: naam, persons, room, extra, remarks
    $person = array(
        "naam"    => unescape($data[0]),
        "persons" => unescape($data[1]),
        "room"    => unescape($data[2]),
        "extra"   => unescape($data[3]),
        "remarks" => unescape($data[4])
    );
    array_push($result, $person);
}
echo(json_encode($result));
fclose($handle);