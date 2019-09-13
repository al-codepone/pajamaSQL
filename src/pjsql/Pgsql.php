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
    
    //
    public function rquery($query) {
        $result = pg_query_params(
            $this->conn(),
            $query,
            array_slice(func_get_args(), 1));

        if($result) {
            return $result;
        }
        
        $this->error();
    }
    
    
    //
    public function prepare($query) {
        $args = func_get_args();
		$num_args = count($args);

        //
        if($num_args != 2) {
            trigger_error('\pjsql\Pgsql::prepare() takes exactly 2 arguments', E_USER_ERROR);
        }
        
        //
        $stmt_name = $args[1];
        $result = pg_prepare($this->conn(), $stmt_name, $query);

        if(!$result) {
			$this->error();
		}
    }
    
    //
    public function bexec($stmt_name) {
        call_user_func_array(
            array($this, 'bindExecute'),
            func_get_args());
    }
    
    //
    public function bquery($stmt_name) {
        
        //
        $result = call_user_func_array(
            array($this, 'bindExecute'),
            func_get_args());

        //
        return pg_fetch_all($result);
    }
    
    //
    public function brquery($stmt_name) {
        
        //
        $result = call_user_func_array(
            array($this, 'bindExecute'),
            func_get_args());

        //
        return $result;
    }

    public function esc($string) {
        return pg_escape_string($this->conn(), $string);
    }

    protected function connError() {
        return pg_last_error($this->conn());
    }
    
    private function bindExecute($stmt_name) {
        
        //
        $result = pg_execute(
            $this->conn(),
            $stmt_name,
            array_slice(func_get_args(), 1));

        if(!$result) {
            $this->error();
        }
        
        return $result;
    }
}
