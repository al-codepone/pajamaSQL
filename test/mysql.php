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
	's',
    'test');

//prepare and then bind multiple times
$stmt = $db->prepare('insert into tword(word) values(?)');
$db->bexec($stmt, 's', 'leopard');
$db->bexec($stmt, 's', 'tiger');
$db->bexec($stmt, 's', 'lion');

//query() returns a 2d array of results
$data = $db->query('SELECT * FROM tword');
echo '<pre>', print_r($data, true), '</pre>';

//a select prepare and multi-bind example, ie. bquery()
$id_floors = array(3, 6, 100);
$stmt = $db->prepare('select * from tword where word_id >= ?');

foreach($id_floors as $f) {
    $data = $db->bquery($stmt, 'i', $f);
    echo '<pre>', print_r($data, true), '</pre>';
}
