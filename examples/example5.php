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

$stmt = $db->prepare('insert into tanimal values(null, ?)');
$db->bexec($stmt, 's', 'bird');
$db->bexec($stmt, 's', 'frog');
$db->bexec($stmt, 's', 'cat');

$ids = [1, 2, 3, 4];
$stmt = $db->prepare('select name from tanimal where animal_id = ?');

foreach($ids as $id) {
    $data = $db->bquery($stmt, 'i', $id);
    print_r($data);
}
