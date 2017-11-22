<?php

require 'vendor/autoload.php';

//handle database error
set_exception_handler('purple\database_error');

//connect to MySQL db
$db = new pjsql\Mysql(
    'host',
    'username',
    'password',
    'db');

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

//escape strings with esc()
$db->exec(sprintf('
    INSERT INTO
        tword(word)
    VALUES
        ("%s")',
    $db->esc('&><\/"')));

//query() returns a 2d array of results
var_dump($db->query('
    SELECT
        *
    FROM
        tword'));
