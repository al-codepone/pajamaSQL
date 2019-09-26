<?php

require 'vendor/autoload.php';

//handle database error
set_exception_handler('purple\database_error');

//connect to MySQL db
$db = new pjsql\Mysql(
    'localhost',
    'root',
    '',
    'test');

//
$db->exec('DROP TABLE IF EXISTS tword');

//exec() executes a result-less query
$db->exec('
    CREATE TABLE tword(
        word_id INT AUTO_INCREMENT PRIMARY KEY,
        word VARCHAR(32))');

$db->exec('
    INSERT INTO
        tword(word)
    VALUES
        ("was"),
        ("jump"),
        ("hello")');

//escape strings with prepared statements
$db->exec('
    INSERT INTO
        tword(word)
    VALUES
        (?)',
    'test');

//prepare and then bind multiple times
$stmt = $db->prepare('insert into tword(word) values(?)');
$db->bexec($stmt, 'leopard');
$db->bexec($stmt, 'tiger');
$db->bexec($stmt, 'lion');

//query() returns a 2d array of results
$data = $db->query('SELECT * FROM tword');
echo '<pre>', print_r($data, true), '</pre>';

//rquery() returns a result object instead of a 2d array
$result = $db->rquery('SELECT * FROM tword limit 3');

while($row = $result->fetch_object()) {
    echo '<pre>', print_r($row, true), '</pre>';
}

//a select prepare and multi-bind example, ie. bquery()
$id_floors = array(3, 6, 100);
$stmt = $db->prepare('select * from tword where word_id >= ?');

foreach($id_floors as $f) {
    $data = $db->bquery($stmt, $f);
    echo '<pre>', print_r($data, true), '</pre>';
}

//brquery()
$less_than = array(2, 3, 7);
$stmt = $db->prepare('select * from tword where word_id < ?');

foreach($less_than as $lt) {
    $result = $db->brquery($stmt, $lt);
    
    while($row = $result->fetch_array()) {
        echo '<pre>', print_r($row, true), '</pre>';
    }
}

//use param types string
$data = $db->query(
    'select * from tword where word_id in(?, ?)',
    [1, 2],
    'ii');

echo '<pre>', print_r($data, true), '</pre>';
