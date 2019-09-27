<?php

require 'boot.php';

$db = new pjsql\Pgsql(sprintf(
    'dbname=%s user=%s password=%s',
    PGSQL_DATABASE,
    PGSQL_USERNAME,
    PGSQL_PASSWORD));

$db->exec('drop table if exists tcolor');

$db->exec('create table tcolor(
    color_id serial primary key,
    name varchar(40))');

$stmt_name = 'insert1';
$db->prepare('insert into tcolor values(default, $1)', $stmt_name);
$colors = ['salmon', 'cyan', 'beige', 'indigo'];

foreach($colors as $c) {
    $db->bexec($stmt_name, $c);
}

$floors = [4, 3];
$stmt_name = 'select1';
$db->prepare('select * from tcolor where color_id >= $1', $stmt_name);

foreach($floors as $f) {
    $result = $db->brquery($stmt_name, $f);

    while($row = pg_fetch_object($result)) {
        print_r($row);
    }
}
