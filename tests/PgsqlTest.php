<?php

use PHPUnit\Framework\TestCase;

//
class PgsqlTest extends TestCase {

    //
    protected static $correct_username = 'postgres';
    protected static $correct_password = 'flower48';
    protected static $correct_database = 'test';
    
    //
    public function testCanConnect() {
        
        //
        $db = new \pjsql\Pgsql(sprintf(
            'dbname=%s user=%s password=%s',
            self::$correct_database,
            self::$correct_username,
            self::$correct_password));

        //
        $this->assertSame(
            get_resource_type($db->conn()),
            'pgsql link');
    }
}
