<?php

namespace pjsql;

class Sqlite extends DatabaseHandle {
    
    //an array const is supposedly 5.6.0+
    private const TYPE_MAP = array(
        'i' => SQLITE3_INTEGER,
        'f' => SQLITE3_FLOAT,
        't' => SQLITE3_TEXT,
        'b' => SQLITE3_BLOB,
        'n' => SQLITE3_NULL);

    //
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
            
            //
            $raw_types = strtolower($args[1]);
            $types = array();
            
            for($i = 0; $i < strlen($raw_types); ++$i) {
                $char = $raw_types[$i];

                if(array_key_exists($char, self::TYPE_MAP)) {
                    $types[] = self::TYPE_MAP[$char];
                }
                else {
                    trigger_error('Invalid SQLite parameter type', E_USER_ERROR);
                }
            }
            
            //
            $values = array_slice($args, 2);
            
            //
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
