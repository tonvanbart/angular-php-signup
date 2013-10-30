<?php
$filename = "ep20.txt";
file_put_contents('php://stderr', "list stored participants\n");

$handle = fopen($filename, 'r');

header('Content-Type: application/javascript');
echo('[');
while (($data = fgetcsv($handle)) !== false) {
    // layout is: naam, persons, room, extra, remarks
    echo('{"naam":"' . $data[0] . '","persons":' . $data[1] . ' ,"room":"' . $data[2] . '","extra":"' . $data[3] . '","remarks":"' . $data[4] . '"},');
}
echo('{}]');
fclose($handle);