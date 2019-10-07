<?php

namespace purple;

class DogModel extends \pjsql\DatabaseAdapter {
    public function install() {
        $this->exec('drop table if exists tdog');

        $this->exec('create table tdog(
            dog_id int auto_increment primary key,
            name varchar(50))');
    }

    public function createDog($name) {
        $this->exec(
            'insert into tdog(name) values(?)',
            $name);
    }

    public function getDogs() {
        return $this->query('
            select
                dog_id, name
            from
                tdog
            order by
                name');
    }     
}
