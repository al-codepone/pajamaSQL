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

$animals = ['cat', 'dog', 'turtle', 'crab', 'shark'];
$stmt = $db->prepare('insert into tanimal values(null, ?)');

foreach($animals as $a) {
    $db->bexec($stmt, $a);
}

$floors = [1, 5];
$stmt = $db->prepare('select name from tanimal where animal_id >= ?');

foreach($floors as $f) {
    $result = $db->brquery($stmt, $f);

    while($row = $result->fetch_assoc()) {
        print_r($row);
    }
}
