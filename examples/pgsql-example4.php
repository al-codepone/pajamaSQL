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

$result = $db->rquery('select * from tcolor');

while($row = pg_fetch_assoc($result)) {
    print_r($row);
}
