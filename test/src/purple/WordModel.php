<?php

namespace purple;

class WordModel extends \pjsql\DatabaseAdapter {
    public function install() {
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
}
