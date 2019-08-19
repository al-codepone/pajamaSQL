<?php

require 'vendor/autoload.php';

//handle database error
set_exception_handler('purple\database_error');

//connect to SQLite db
$db = new pjsql\Sqlite('mydb.db');

//exec() executes a result-less query
$db->exec('drop table if exists tword');

$db->exec('CREATE TABLE tword (word TEXT)');

$db->exec("INSERT INTO tword (word)
    SELECT 'was' AS 'word'
    UNION SELECT 'jump'
    UNION SELECT 'hello'");

//escape strings with esc()
$db->exec(sprintf("INSERT INTO tword (word) VALUES('%s')",
    $db->esc("it's")));

$db->exec(
    'insert into tword(word) values(?)',
    't',
    'frog');

$db->exec(
    "insert into tword(word)
        select ? as 'word' union
        select ?",
    'tt',
    'word1',
    'word2');

//query() returns a 2d array of results
$data = $db->query(
    'SELECT rowid, word FROM tword where rowid > ? and rowid <= ?',
    'ii',
    1,
    4);

echo '<pre>',
    print_r($data, true) .
    '</pre>';
