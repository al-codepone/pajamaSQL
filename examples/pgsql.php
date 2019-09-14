<?php

require 'vendor/autoload.php';

//handle database error
set_exception_handler('purple\database_error');

//connect to PostgreSQL db
$db = new pjsql\Pgsql('dbname=test user=postgres password=flower48');

//
$db->exec('drop table if exists tword');

//exec() executes a result-less query
$db->exec('CREATE TABLE tword (
    word_id SERIAL PRIMARY KEY,
    word VARCHAR(64))');

$db->exec("INSERT INTO tword (word) VALUES('jump')");

//
$db->exec('insert into tword(word) values($1)', ')))))');

//escape strings with esc()
$db->exec(sprintf("INSERT INTO tword (word) VALUES('%s')",
    $db->esc('&><\/\'')));

//prepare and bind multiple times
$stmt_name = 'stmt1';
$db->prepare('insert into tword(word) values($1)', $stmt_name);
$db->bexec($stmt_name, 'eight');
$db->bexec($stmt_name, 'nine');
$db->bexec($stmt_name, 'twenty');

//query() returns a 2d array of results
echo '<pre>',
    print_r($db->query('SELECT * FROM tword'), true),
    '</pre>';

//
$data = $db->query(
    'select * from tword where word_id < $1 and word_id != $2',
    4,
    2);

echo '<pre>',
    print_r($data, true),
    '</pre>';

//rquery()
$result = $db->rquery('select * from tword limit 2');

while($row = pg_fetch_assoc($result)) {
    echo '<pre>', print_r($row, true), '</pre>';
}

//select prepare and bind multiple times
$ranges = [
    [1, 2],
    [2, 100],
    [6, 6]];

$stmt_name = 'stmt2';
$db->prepare('select * from tword where word_id >= $1 and word_id <= $2', $stmt_name);

foreach($ranges as $r) {
    list($min, $max) = $r;
    $data = $db->bquery($stmt_name, $min, $max);
    echo '<pre>', print_r($data, true), '</pre>';
}

//brquery()
$my_ids = array(1, 2, 3);
$stmt_name = 'stmt3';

$db->prepare('select word from tword where word_id = $1', $stmt_name);

foreach($my_ids as $i) {
    $result = $db->brquery($stmt_name, $i);
    
    while($row = pg_fetch_row($result)) {
        echo '<pre>', print_r($row, true), '</pre>';
    }
}
