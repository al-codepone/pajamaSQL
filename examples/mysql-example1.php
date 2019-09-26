<?php

require 'boot.php';

$db = new pjsql\Mysql(
    MYSQL_HOST,
    MYSQL_USERNAME,
    MYSQL_PASSWORD,
    MYSQL_DATABASE);

echo $db->conn()->stat();
