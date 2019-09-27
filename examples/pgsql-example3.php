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

$db->exec(
    'insert into tcolor(name) values($1), ($2)',
    'gold',
    'silver');

$data = $db->query(
    'select * from tcolor where color_id <> $1',
    1);

print_r($data);
