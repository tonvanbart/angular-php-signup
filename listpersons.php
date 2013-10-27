<?php
$filename = "ep20.txt";
file_put_contents('php://stderr', "list stored participants\n");

$handle = fopen($filename, 'r');

header('Content-Type: application/javascript');
echo('[');
while (($data = fgetcsv($handle)) !== false) {
    // layout is: naam, persons, room, remarks
    echo('{"naam":"' . $data[0] . '","persons":' . $data[1] . ' ,"room":"' . $data[2] . '","remarks":"' . $data[3] . '"},');
}
echo('{}]');
fclose($handle);