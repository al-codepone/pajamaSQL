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
        call_user_func_array(
            array($this, 'prepareBindExecute'),
            func_get_args());
	}

    public function query($query) {

        //
        $stmt = call_user_func_array(
            array($this, 'prepareBindExecute'),
            func_get_args());

        //
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    //
    public function rquery($query) {
        $stmt = call_user_func_array(
            array($this, 'prepareBindExecute'),
            func_get_args());

        return $stmt->get_result();
    }
    
    //
    public function prepare($query) {
        
        //
        $stmt = mysqli_prepare($this->conn(), $query);

        if(!$stmt) {
			throw new DatabaseException($this->conn()->error, $this->conn()->errno);
		}
        
        return $stmt;
    }
    
    //
    public function bexec($stmt) {
        call_user_func_array(
            array($this, 'bindExecute'),
            func_get_args());
    }
    
    //
    public function bquery($stmt) {
        
        //
        $stmt = call_user_func_array(
            array($this, 'bindExecute'),
            func_get_args());

        //
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    //
    public function brquery($stmt) {
        
        //
        $stmt = call_user_func_array(
            array($this, 'bindExecute'),
            func_get_args());

        //
        return $stmt->get_result();
    }

    public function esc($string) {
        return mysqli_real_escape_string($this->conn(), $string);
    }

    protected function connError() {
        return $this->conn()->error;
    }
    
    private function prepareBindExecute($query) {
        
        //prepare
        $stmt = $this->prepare($query);
        
        //
        $args = array_merge(
            array($stmt),
            array_slice(func_get_args(), 1));

        //bind and execute
        call_user_func_array(
            array($this, 'bindExecute'),
            $args);

        //
        return $stmt;
    }
    
    private function bindExecute($stmt) {

        //bind
		$args = func_get_args();
		$num_args = count($args);
        
        if($num_args > 1) {
            
            //
            $bind_args = array();
            $is_params_array = is_array($args[1]);
            
            //
            if($is_params_array) {
                
                //
                $params = $args[1];
                $types = ($num_args == 2)
                    ? str_repeat('s', count($params))
                    : $args[2];

                $bind_args[] = $types;

                //
                for($i = 0; $i < count($params); ++$i) {
                    $bind_args[] = &$params[$i];
                }
            }
            else {
                $bind_args[] = str_repeat('s', $num_args - 1);
                
                for($i = 1; $i < $num_args; ++$i) {
                    $bind_args[] = &$args[$i];
                }
            }
            
            //
            if(!call_user_func_array(array($stmt, 'bind_param'), $bind_args)) {
				throw new DatabaseException($stmt->error, $stmt->errno);
			}
        }

		//execute
		if(!$stmt->execute()) {
			throw new DatabaseException($stmt->error, $stmt->errno);
		}
        
        //
        return $stmt;
    }
}
