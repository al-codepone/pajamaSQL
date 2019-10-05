<?php

require 'boot.php';

$db = new pjsql\Sqlite(SQLITE_DATABASE);

echo get_class($db->conn());
