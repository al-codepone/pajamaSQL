<?php

use PHPUnit\Framework\TestCase;

//
class MysqlTest extends TestCase {
    
    //
    public function testCanConnect() {
        
        //
        $correct_host = 'localhost';
        $correct_username = 'root';
        $correct_password = '';
        $correct_database = 'test';
        
        //
        $db = new \pjsql\Mysql(
            $correct_host,
            $correct_username,
            $correct_password,
            $correct_database);

        //
        $this->assertSame(
            get_class($db->conn()),
            mysqli::class);
    }
}
