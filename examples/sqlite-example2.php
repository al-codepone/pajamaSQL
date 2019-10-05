<?php

require 'boot.php';

$db = new pjsql\Sqlite(SQLITE_DATABASE);

$db->exec('drop table if exists tshape');

$db->exec('create table tshape(name text)');

$db->exec('insert into tshape values("circle")');

$data = $db->query('select * from tshape');

print_r($data);
