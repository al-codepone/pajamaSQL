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
    
    //
    public function rquery($query) {
        $result = call_user_func_array(
            array($this, 'prepareBindExecute'),
            func_get_args());

        return $result;
    }
    
    //
    public function prepare($query) {
        
        //
        $stmt = $this->conn()->prepare($query);
        
        if(!$stmt) {
            $this->error();
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
        $result = call_user_func_array(
            array($this, 'bindExecute'),
            func_get_args());

        $rows = array();

        while($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $rows[] = $row;
        }

        return $rows;
    }
    
    //
    public function brquery($stmt) {
        $result = call_user_func_array(
            array($this, 'bindExecute'),
            func_get_args());

        return $result;
    }

    public function esc($string) {
        return \SQLite3::escapeString($string);
    }

    protected function connError() {
        return $this->conn()->lastErrorMsg();
    }
    
    private function prepareBindExecute($query) {

        //prepare
        $stmt = $this->prepare($query);
        
        //
        $args = array_merge(
            array($stmt),
            array_slice(func_get_args(), 1));

        //bind and execute
        $result = call_user_func_array(
            array($this, 'bindExecute'),
            $args);

        //
        return $result;
    }

    //
    private function bindExecute($stmt) {
        
        //bind
		$args = func_get_args();
		$num_args = count($args);

        //
        if($num_args > 1) {
            
            //
            $is_params_array = is_array($args[1]);
            
            //
            if($is_params_array) {
                
                //
                $params = $args[1];
                $raw_types = ($num_args == 2)
                    ? str_repeat('t', count($params))
                    : $args[2];
            }
            else {
                $params = array_slice($args, 1);
                $raw_types = str_repeat('t', count($params));
            }
            
            //
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
            $stmt->reset();

            foreach($types as $i => $t) {
                if(!$stmt->bindValue($i + 1, $params[$i], $t)) {
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
