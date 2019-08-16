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

//query() returns a 2d array of results
var_dump($db->query('SELECT * FROM tword'));

//
var_dump($db->query(
    'select * from tword where word_id < $1 and word_id != $2',
    100,
    2));
