<?php

require 'boot.php';

$db = new pjsql\Mysql(
    MYSQL_HOST,
    MYSQL_USERNAME,
    MYSQL_PASSWORD,
    MYSQL_DATABASE);

$db->exec('drop table if exists tanimal');

$db->exec('create table tanimal(
    animal_id int auto_increment primary key,
    name varchar(32))');

$db->exec(
    'insert into tanimal(name) values(?), (?), (?)',
    ['lizard', 'cow', 'monkey'],
    'sss');

$data = $db->query(
    'select * from tanimal where animal_id < ?',
    [3],
    'i');

print_r($data);
