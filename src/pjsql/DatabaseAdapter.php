<?php

namespace pjsql;

abstract class DatabaseAdapter {
    private $databaseHandle;

    public function __construct(DatabaseHandle $databaseHandle) {
        $this->databaseHandle = $databaseHandle;
    }

    protected function exec($query) {
        $this->databaseHandle->exec($query);
    }

    protected function query($query) {
        return $this->databaseHandle->query($query);
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
