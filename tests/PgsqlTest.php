<?php

use PHPUnit\Framework\TestCase;

//
class PgsqlTest extends TestCase {

    //
    protected $correct_username = 'postgres';
    protected $correct_password = 'flower48';
    protected $correct_database = 'test';
    
    //
    public function testCanConnect() {
        
        //
        $db = new \pjsql\Pgsql(sprintf(
            'dbname=%s user=%s password=%s',
            $this->correct_database,
            $this->correct_username,
            $this->correct_password));

        //
        $this->assertSame(
            get_resource_type($db->conn()),
            'pgsql link');
    }
}
