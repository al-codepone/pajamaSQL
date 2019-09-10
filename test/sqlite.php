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

//prepare and then bind exec more than once
$stmt = $db->prepare('insert into tword(word) values(?)');
$db->bexec($stmt, 't', 'up');
$db->bexec($stmt, 't', 'down');

//query() returns a 2d array of results
$data = $db->query(
    'SELECT rowid, word FROM tword where rowid > ? and rowid <= ?',
    'ii',
    1,
    4);

echo '<pre>',
    print_r($data, true) .
    '</pre>';

//prepare and bind query more than once
$only_these = array(
    array(1, 2, 3),
    array(4, 5, 6),
    array(1, 100, 1000));

$stmt = $db->prepare('select rowid, word from tword where rowid in(?, ?, ?)');

foreach($only_these as $o) {
    $data = $db->bquery($stmt, 'iii', $o[0], $o[1], $o[2]);
    
    echo '<pre>',
        print_r($data, true) .
        '</pre>';
}
