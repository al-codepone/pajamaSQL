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
    
    //
    public function testExecQuery() {

        //
        $db = new \pjsql\Pgsql(sprintf(
            'dbname=%s user=%s password=%s',
            self::$correct_database,
            self::$correct_username,
            self::$correct_password));

        $db->exec('drop table if exists tfish');
        $db->exec('create table tfish(name varchar(50))');
        $db->exec("insert into tfish values('trout'), ('bass'), ('tetra')");

        $data = $db->query('select * from tfish');

        $this->assertSame(
            count($data),
            3);
    }
}
