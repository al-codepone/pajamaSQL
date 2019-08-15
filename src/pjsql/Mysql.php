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

    public function esc($string) {
        return mysqli_real_escape_string($this->conn(), $string);
    }

    protected function connError() {
        return $this->conn()->error;
    }
    
    private function prepareBindExecute($query) {

        //prepare
		if(!($stmt = mysqli_prepare($this->conn(), $query))) {
			throw new DatabaseException($this->conn()->error, $this->conn()->errno);
		}

		//bind
		$args = func_get_args();
		$num_args = count($args);

		if($num_args > 2) {
			$params = array($args[1]);

			for($i = 2; $i < $num_args; ++$i) {
				$params[] = &$args[$i];
			}

			if(!call_user_func_array(array($stmt, 'bind_param'), $params)) {
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
