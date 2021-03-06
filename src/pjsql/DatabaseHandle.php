<?php

namespace pjsql;

abstract class DatabaseHandle {
    private $conn;

    public function __construct($conn) {
        if(!$conn) {
            $this->error();
        }

        $this->conn = $conn;
    }

    abstract public function exec($query);
    abstract public function query($query);
    abstract public function rquery($query);
    abstract public function prepare($query);
    abstract public function bexec($stmt);
    abstract public function bquery($stmt);
    abstract public function brquery($stmt);
    abstract public function esc($string);

    public function conn() {
        return $this->conn;
    }

    public function error() {
        throw new DatabaseException($this->connError());
    }

    abstract protected function connError();
}
