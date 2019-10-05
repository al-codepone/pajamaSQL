<?php

require 'boot.php';

$db = new pjsql\Sqlite(SQLITE_DATABASE);

$db->exec('drop table if exists tshape');

$db->exec('create table tshape(name text)');

$db->exec(
    'insert into tshape values(?), (?)',
    ['square', 'circle'],
    'tt');

$data = $db->query(
    'select * from tshape where rowid < ?',
    [500],
    'i');

print_r($data);
