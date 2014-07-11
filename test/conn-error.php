<?php

require 'vendor/autoload.php';

set_exception_handler('purple\database_error');

$db = new pjsql\Mysql(
    'host',
    'username',
    'password',
    'db');

//access full vendor specific database extension via conn()
if($status = $db->conn()->stat()) {
    echo $status;
}
else {
    //call error() when using conn()
    $db->error();
}
