<?php

namespace pjsql;

class Sqlite extends DatabaseHandle {
    public function __construct($filename, $flags = 6, $encryptionKey = '') {
        $conn = new \SQLite3($filename, $flags, $encryptionKey);
        parent::__construct($conn);
    }

    public function exec($query) {
        if(!$this->conn()->exec($query)) {
            $this->error();
        }
    }

    public function query($query) {
        if($result = $this->conn()->query($query)) {
            $rows = array();

            while($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $rows[] = $row;
            }

            return $rows;
        }

        $this->error();
    }

    public function esc($string) {
        return \SQLite3::escapeString($string);
    }

    protected function connError() {
        return $this->conn()->lastErrorMsg();
    }
}
