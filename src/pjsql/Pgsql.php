<?php

namespace pjsql;

class Pgsql extends DatabaseHandle {
    public function __construct(
        $connectionString,
        $connectType = null,
        $errorMessage = 'database error',
        $debug = false)
    {
        $conn = pg_connect($connectionString, $connectType);
        parent::__construct($errorMessage, $debug, $conn);
    }

    public function exec($query) {
        if(!pg_query($this->conn(), $query)) {
            $this->error();
        }
    }

    public function query($query) {
        if($result = pg_query($this->conn(), $query)) {
            $rows = array();

            while($row = pg_fetch_assoc($result)) { 
                $rows[] = $row;
            }

            return $rows;
        }

        $this->error();
    }

    public function esc($string) {
        return pg_escape_string($this->conn(), $string);
    }

    protected function connError() {
        return pg_last_error($this->conn());
    }
}
