<?php

namespace purple;

function database_error($e) {
    if($e instanceof \pjsql\DatabaseException) {
        die($e->getMessage());
    }
    else {
        throw $e;
    }
}
