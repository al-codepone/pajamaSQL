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
$db->bexec($stmt_name, 'pink');
$db->bexec($stmt_name, 'purple');
$db->bexec($stmt_name, 'black');

$ids = [1, 2, 3, 4];
$stmt_name = 'select1';
$db->prepare('select name from tcolor where color_id = $1', $stmt_name);

foreach($ids as $id) {
    $data = $db->bquery($stmt_name, $id);
    var_dump($data);
}
