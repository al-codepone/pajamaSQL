<?php

namespace pjsql;

abstract class DatabaseHandle {
    private $errorMessage;
    private $debug;
    private $conn;

    public function __construct($errorMessage, $debug, $conn) {
        $this->errorMessage = $errorMessage;
        $this->debug = $debug;

        if(!$conn) {
            $this->error();
        }

        $this->conn = $conn;
    }

    abstract public function exec($query);
    abstract public function query($query);
    abstract public function esc($string);

    public function conn() {
        return $this->conn;
    }

    public function error() {
        die($this->debug ? $this->connError() : $this->errorMessage);
    }

    abstract protected function connError();
}
