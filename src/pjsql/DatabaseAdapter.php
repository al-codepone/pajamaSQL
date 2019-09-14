<?php

namespace pjsql;

abstract class DatabaseAdapter {
    private $databaseHandle;

    public function __construct(DatabaseHandle $databaseHandle) {
        $this->databaseHandle = $databaseHandle;
    }

    protected function exec($query) {
        call_user_func_array(
            array($this->databaseHandle, 'exec'),
            func_get_args());
    }

    protected function query($query) {
        return call_user_func_array(
            array($this->databaseHandle, 'query'),
            func_get_args());
    }

    protected function rquery($query) {
        return call_user_func_array(
            array($this->databaseHandle, 'rquery'),
            func_get_args());
    }
    
    protected function prepare($query) {
        return call_user_func_array(
            array($this->databaseHandle, 'prepare'),
            func_get_args());
    }
    
    protected function bexec($stmt) {
        call_user_func_array(
            array($this->databaseHandle, 'bexec'),
            func_get_args());
    }
    
    protected function bquery($stmt) {
        return call_user_func_array(
            array($this->databaseHandle, 'bquery'),
            func_get_args());
    }
    
    protected function brquery($stmt) {
        return call_user_func_array(
            array($this->databaseHandle, 'brquery'),
            func_get_args());
    }

    protected function esc($string) {
        return $this->databaseHandle->esc($string);
    }

    protected function conn() {
        return $this->databaseHandle->conn();
    }

    protected function error() {
        $this->databaseHandle->error();
    }
}
