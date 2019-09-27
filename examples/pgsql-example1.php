<?php

require 'boot.php';

$db = new pjsql\Pgsql(sprintf(
    'dbname=%s user=%s password=%s',
    PGSQL_DATABASE,
    PGSQL_USERNAME,
    PGSQL_PASSWORD));

echo pg_host($db->conn());
