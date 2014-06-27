<?php

namespace pjsql;

class Mysql extends DatabaseHandle {
    public function __construct(
        $host = null,
        $username = null,
        $password = null,
        $databaseName = '',
        $port = null,
        $socket = null)
    {
        $conn = mysqli_connect($host, $username, $password, $databaseName, $port, $socket);
        parent::__construct($conn);
    }

    public function exec($query) {
        if(!mysqli_query($this->conn(), $query)) {
            $this->error();
        }
    }

    public function query($query) {
        if($result = mysqli_query($this->conn(), $query)) {
            $rows = array();

            while($row = mysqli_fetch_assoc($result)) { 
                $rows[] = $row;
            }

            return $rows;
        }

        $this->error();
    }

    public function esc($string) {
        return mysqli_real_escape_string($this->conn(), $string);
    }

    protected function connError() {
        return $this->conn()->error;
    }
}
