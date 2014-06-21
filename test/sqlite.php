<?php

require 'vendor/autoload.php';

//connect to SQLite db
$db = new pjsql\Sqlite('mydb.db');

//exec() executes a result-less query
$db->exec('CREATE TABLE tword (word TEXT)');

$db->exec("INSERT INTO tword (word)
    SELECT 'was' AS 'word'
    UNION SELECT 'jump'
    UNION SELECT 'hello'");

//escape strings with esc()
$db->exec(sprintf("INSERT INTO tword (word) VALUES('%s')",
    $db->esc("it's")));

//query() returns a 2d array of results
var_dump($db->query('SELECT rowid, word FROM tword'));
