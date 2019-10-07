<?php

use PHPUnit\Framework\TestCase;

//
class SqliteTest extends TestCase {

    //
    protected static $database = 'mydb.db';
    
    //
    public function testCanConnect() {
        
        //
        $db = new \pjsql\Sqlite(self::$database);

        //
        $this->assertSame(
            get_class($db->conn()),
            SQLite3::class);
    }

    //
    public function testExecQuery() {
        $db = new \pjsql\Sqlite(self::$database);
        $db->exec('drop table if exists tfood');
        $db->exec('create table tfood(name text)');
        $db->exec('insert into tfood values("apple"), ("rice")');

        $data = $db->query('select * from tfood');

        $this->assertSame(
            count($data),
            2);
    }
}
