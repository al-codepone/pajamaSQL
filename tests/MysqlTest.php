<?php

use PHPUnit\Framework\TestCase;

//
class MysqlTest extends TestCase {

    //
    protected static $correct_host = 'localhost';
    protected static $correct_username = 'root';
    protected static $correct_password = '';
    protected static $correct_database = 'test';
    
    //
    public function testCanConnect() {
        
        //
        $db = new \pjsql\Mysql(
            self::$correct_host,
            self::$correct_username,
            self::$correct_password,
            self::$correct_database);

        //
        $this->assertSame(
            get_class($db->conn()),
            mysqli::class);
    }
}
