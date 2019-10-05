<?php

require 'boot.php';

$db = new pjsql\Sqlite(SQLITE_DATABASE);

$db->exec('drop table if exists tshape');

$db->exec('create table tshape(name text)');

$stmt = $db->prepare('insert into tshape values(?)');
$shapes = ['star', 'heart', 'cross', 'diamond'];

foreach($shapes as $s) {
    $db->bexec($stmt, $s);
}

$stmt = $db->prepare('select * from tshape where rowid >= ?');
$floors = [4, 3, 4];

foreach($floors as $f) {
    $result = $db->brquery($stmt, $f);

    while($row = $result->fetchArray()) {
        print_r($row);
    }
}
