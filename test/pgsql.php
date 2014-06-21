<?php

require 'vendor/autoload.php';

//connect to PostgreSQL db
$db = new pjsql\Pgsql(
    'dbname=mydb user=jon password=123456');

//exec() executes a result-less query
$db->exec('CREATE TABLE tword (
    word_id SERIAL PRIMARY KEY,
    word VARCHAR(64))');

$db->exec("INSERT INTO tword (word) VALUES('jump')");

//escape strings with esc()
$db->exec(sprintf("INSERT INTO tword (word) VALUES('%s')",
    $db->esc('&><\/\'')));

//query() returns a 2d array of results
var_dump($db->query('SELECT * FROM tword'));
