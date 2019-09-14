<?php

namespace purple;

class ModelFactory extends \pjsql\AdapterFactory {
    protected static function databaseHandle() {

        //all models share a single instance of this object
        return new \pjsql\Mysql(
            'localhost',
            'root',
            '',
            'test');
    }
}
