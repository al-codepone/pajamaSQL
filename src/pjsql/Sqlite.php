<?php

namespace pjsql;

class Sqlite extends DatabaseHandle {
    public function __construct($filename, $flags = 6, $encryptionKey = '') {
        $conn = new \SQLite3($filename, $flags, $encryptionKey);
        parent::__construct($conn);
    }
    
    public function exec($query) {
        call_user_func_array(
            array($this, 'prepareBindExecute'),
            func_get_args());
    }
    
    public function query($query) {
        $result = call_user_func_array(
            array($this, 'prepareBindExecute'),
            func_get_args());

        $rows = array();

        while($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function esc($string) {
        return \SQLite3::escapeString($string);
    }

    protected function connError() {
        return $this->conn()->lastErrorMsg();
    }
    
    private function prepareBindExecute($query) {

        //prepare
        $stmt = $this->conn()->prepare($query);
        
        if(!$stmt) {
            $this->error();
        }
        
        //bind
		$args = func_get_args();
		$num_args = count($args);

		if($num_args > 2) {
            $types = $args[1];
            $values = array_slice($args, 2);
            
            foreach($types as $i => $t) {
                if(!$stmt->bindValue($i + 1, $values[$i], $t)) {
                    $this->error();
                }
            }
		}
        
        //execute
        $result = $stmt->execute();
        
        if(!$result) {
            $this->error();
        }
        
        return $result;
    }
}
