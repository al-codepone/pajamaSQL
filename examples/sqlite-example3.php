<?php

require 'boot.php';

$db = new pjsql\Sqlite(SQLITE_DATABASE);

$db->exec('drop table if exists tshape');

$db->exec('create table tshape(name text)');

$db->exec(
    'insert into tshape values(?), (?)',
    'triangle',
    'square');

$data = $db->query(
    'select * from tshape where rowid > ?',
    1);

print_r($data);
