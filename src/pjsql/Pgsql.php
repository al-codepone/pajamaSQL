<?php

namespace pjsql;

class Pgsql extends DatabaseHandle {
    public function __construct($connectionString, $connectType = null) {
        $conn = pg_connect($connectionString, $connectType);
        parent::__construct($conn);
    }

    public function exec($query) {
        $result = pg_query_params(
            $this->conn(),
            $query,
            array_slice(func_get_args(), 1));

        if(!$result) {
            $this->error();
        }
    }

    public function query($query) {
        $result = pg_query_params(
            $this->conn(),
            $query,
            array_slice(func_get_args(), 1));

        if($result) {
            return pg_fetch_all($result);
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
