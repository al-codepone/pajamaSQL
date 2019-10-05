<?php

require 'boot.php';

$db = new pjsql\Sqlite(SQLITE_DATABASE);

$db->exec('drop table if exists tshape');

$db->exec('create table tshape(name text)');

$stmt = $db->prepare('insert into tshape values(?)');
$db->bexec($stmt, 'octagon');
$db->bexec($stmt, 'oval');
$db->bexec($stmt, 'circle');

$ids = [1, 2, 3, 4];
$stmt = $db->prepare('select name from tshape where rowid = ?');

foreach($ids as $id) {
    $data = $db->bquery($stmt, $id);
    print_r($data);
}
