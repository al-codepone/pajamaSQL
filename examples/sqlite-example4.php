<?php

require 'boot.php';

$db = new pjsql\Sqlite(SQLITE_DATABASE);

$db->exec('drop table if exists tshape');

$db->exec('create table tshape(name text)');

$db->exec(
    'insert into tshape values(?), (?)',
    'triangle',
    'square');

$result = $db->rquery('select * from tshape');

while($row = $result->fetchArray(SQLITE3_ASSOC)) {
    print_r($row);
}
