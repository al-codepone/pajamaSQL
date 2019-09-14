<?php

namespace purple;

class WordModel extends \pjsql\DatabaseAdapter {
    public function install() {
        
        //
        $this->exec('DROP TABLE IF EXISTS tword');

        $this->exec('CREATE TABLE tword (
            word_id INT AUTO_INCREMENT PRIMARY KEY,
            word VARCHAR(32))');
    }

    public function create(array $words) {
        if($words) {
            $this->exec(sprintf('INSERT INTO tword (word) VALUES("%s")',
                implode(
                    '"), ("',
                    array_map(
                        array($this, 'esc'),
                        $words))));
        }
    }

    public function get() {
        return $this->query('SELECT * FROM tword');
    }

    public function update($from, $to) {
        $this->exec(sprintf('UPDATE tword SET word = "%s" WHERE word = "%s"',
            $this->esc($to),
            $this->esc($from)));
    }

    public function delete($word) {
        $this->exec(sprintf('DELETE FROM tword WHERE word = "%s"',
            $this->esc($word)));
    }
    
    //
    public function runMainExampleInModel() {

        //
        $this->exec('DROP TABLE IF EXISTS tword');

        //exec() executes a result-less query
        $this->exec('
            CREATE TABLE tword(
                word_id INT AUTO_INCREMENT PRIMARY KEY,
                word VARCHAR(32))');

        $this->exec('
            INSERT INTO
                tword(word)
            VALUES
                ("was"),
                ("jump"),
                ("hello")');

        //escape strings with prepared statements
        $this->exec('
            INSERT INTO
                tword(word)
            VALUES
                (?)',
            's',
            'test');

        //prepare and then bind multiple times
        $stmt = $this->prepare('insert into tword(word) values(?)');
        $this->bexec($stmt, 's', 'leopard');
        $this->bexec($stmt, 's', 'tiger');
        $this->bexec($stmt, 's', 'lion');

        //query() returns a 2d array of results
        $data = $this->query('SELECT * FROM tword');
        echo '<pre>', print_r($data, true), '</pre>';

        //rquery() returns a result object instead of a 2d array
        $result = $this->rquery('SELECT * FROM tword limit 3');

        while($row = $result->fetch_object()) {
            echo '<pre>', print_r($row, true), '</pre>';
        }

        //a select prepare and multi-bind example, ie. bquery()
        $id_floors = array(3, 6, 100);
        $stmt = $this->prepare('select * from tword where word_id >= ?');

        foreach($id_floors as $f) {
            $data = $this->bquery($stmt, 'i', $f);
            echo '<pre>', print_r($data, true), '</pre>';
        }

        //brquery()
        $less_than = array(2, 3, 7);
        $stmt = $this->prepare('select * from tword where word_id < ?');

        foreach($less_than as $lt) {
            $result = $this->brquery($stmt, 'i', $lt);
            
            while($row = $result->fetch_array()) {
                echo '<pre>', print_r($row, true), '</pre>';
            }
        }
    }
}
