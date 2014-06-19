<?php

namespace pjsql;

abstract class AdapterFactory {
    private static $databaseHandle;

    public static function get($adapterName) {
        if(!self::$databaseHandle) {
            self::$databaseHandle = static::databaseHandle();
        }

        return new $adapterName(self::$databaseHandle);
    }

    abstract protected static function databaseHandle();
}
