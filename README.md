# pajamaSQL

pajamaSQL is a PHP SQL database layer.
It supports MySQL, PostgreSQL and SQLite.

## Documentation

This [README](https://github.com/al-codepone/pajamaSQL/blob/master/README.md)
is currently the only documentation.

## Requirements

**PHP 5.3** or higher, [Composer](https://getcomposer.org/),
and at least one of the follow PHP vendor specific database extensions:
[Mysqli](http://www.php.net/manual/en/book.mysqli.php),
[PostgreSQL](http://www.php.net/manual/en/book.pgsql.php),
[SQLite3](http://www.php.net/manual/en/book.sqlite3.php).


## Source Code

This [project](https://github.com/al-codepone/pajamaSQL)
and its [source code](https://github.com/al-codepone/pajamaSQL/tree/master/src/pjsql)
are available on GitHub.

## Tests

All the tests are in the [test directory](https://github.com/al-codepone/pajamaSQL/tree/master/test).
Each PHP script in the top level test directory is a separate test.
You need to run `composer install` in the test directory before running any of the tests.
You also need to setup database credentials and put them in a test before running any test.

## Installation

Install using composer:

```javascript
{
    "require": {
        "pajamasql/pajamasql": "0.5.0",
    }
}
```

## Overview

pajamaSQL wraps [Mysqli](http://www.php.net/manual/en/book.mysqli.php),
[PostgreSQL](http://www.php.net/manual/en/book.pgsql.php) and
[SQLite3](http://www.php.net/manual/en/book.sqlite3.php)
prepared statements. Regardless of which SQL you use,
these ten methods are available: `exec()`, `query()`, `rquery()`, `prepare()`,
`bexec()`, `bquery()`, `brquery()`, `conn()`, `error()` and `esc()`.
Basic usage looks like this:

```php
$db->exec(
    'insert into foo values(?), (?)',
    'bar',
    'baz');

$data = $db->query(
    'select * from foo where rowid > ?',
    1);
```

There is also a model layer that exposes the above ten methods to `$this`.

## MySQL

Connect to MySQL using the constructor:

```php
<?php

require 'vendor/autoload.php';

$db = new pjsql\Mysql(
    'host',
    'username',
    'password',
    'database');

echo $db->conn()->stat();
```

Execute MySQL queries with `exec()` and `query()`:

```php
$db->exec('drop table if exists tanimal');

$db->exec('create table tanimal(
    animal_id int auto_increment primary key,
    name varchar(32))');

$db->exec('insert into tanimal(name) values("tiger")');

$data = $db->query('select * from tanimal');

print_r($data);
```

`exec()` and `query()` can take query parameters:

```php
$db->exec(
    'insert into tanimal(name) values(?), (?)',
    'tiger',
    'eagle');

$data = $db->query(
    'select * from tanimal where animal_id = ?',
    2);

print_r($data);
```

`query()` returns a 2D array. If you want a `mysqli_result` object instead then use `rquery()`:

```php
$db->exec(
    'insert into tanimal(name) values(?), (?)',
    'tiger',
    'eagle');

$result = $db->rquery(
    'select * from tanimal where animal_id < ?',
    1000);

while($row = $result->fetch_object()) {
    print_r($row);
}
```

Use `prepare()`, `bexec()` and `bquery()` to run a query more than once:

```php
$stmt = $db->prepare('insert into tanimal values(null, ?)');
$db->bexec($stmt, 'bird');
$db->bexec($stmt, 'frog');
$db->bexec($stmt, 'cat');

$ids = [1, 2, 3, 4];
$stmt = $db->prepare('select name from tanimal where animal_id = ?');

foreach($ids as $id) {
    $data = $db->bquery($stmt, $id);
    print_r($data);
}
```

`bquery()` returns a 2D array. If you want a `mysqli_result` object instead then use `brquery()`:

```php
$animals = ['cat', 'dog', 'turtle', 'crab', 'shark'];
$stmt = $db->prepare('insert into tanimal values(null, ?)');

foreach($animals as $a) {
    $db->bexec($stmt, $a);
}

$floors = [1, 5];
$stmt = $db->prepare('select name from tanimal where animal_id >= ?');

foreach($floors as $f) {
    $result = $db->brquery($stmt, $f);

    while($row = $result->fetch_assoc()) {
        print_r($row);
    }
}
```

With `exec()`, `query()`, `rquery()`, `bexec()`, `bquery()` and `brquery()`
you can put the parameter values into an array and use a types
string as the third argument in order to specify the query
parameter types:

```php
$db->exec(
    'insert into tanimal(name) values(?), (?), (?)',
    ['lizard', 'cow', 'monkey'],
    'sss');

$data = $db->query(
    'select * from tanimal where animal_id < ?',
    [3],
    'i');

print_r($data);
```

The parameter types string above works the same way as the
[mysqli_stmt::bind_param](https://www.php.net/manual/en/mysqli-stmt.bind-param.php)
`$types` argument.

## PostgreSQL

Use the `Pgsql()` constructor to connect to a PostgreSQL database:

```php
<?php

require 'vendor/autoload.php';

$db = new pjsql\Pgsql('dbname=foo user=bar password=baz');

echo pg_host($db->conn());
```

`conn()` above is a PostgreSQL connection resource that is returned
by [pg_connect()](https://www.php.net/manual/en/function.pg-connect.php).

Execute PostgreSQL queries with `exec()` and `query()`:

```php
$db->exec('drop table if exists tcolor');

$db->exec('create table tcolor(
    color_id serial primary key,
    name varchar(40))');

$db->exec("insert into tcolor(name) values('green')");

$data = $db->query('select * from tcolor');

print_r($data);
```

`exec()` and `query()` can take query parameters:

```php
$db->exec(
    'insert into tcolor(name) values($1), ($2)',
    'gold',
    'silver');

$data = $db->query(
    'select * from tcolor where color_id <> $1',
    1);

print_r($data);
```

`query()` returns an array with all the data.
If you want a query result resource instead then use `rquery()`:

```php
$db->exec(
    'insert into tcolor(name) values($1), ($2)',
    'gold',
    'silver');

$result = $db->rquery(
    'select * from tcolor where color_id > $1',
    0);

while($row = pg_fetch_assoc($result)) {
    print_r($row);
}
```

Use `prepare()`, `bexec()` and `bquery()` to run a query more than once:

```php
$stmt_name = 'insert1';
$db->prepare('insert into tcolor values(default, $1)', $stmt_name);
$db->bexec($stmt_name, 'pink');
$db->bexec($stmt_name, 'purple');
$db->bexec($stmt_name, 'black');

$ids = [1, 2, 3, 4];
$stmt_name = 'select1';
$db->prepare('select name from tcolor where color_id = $1', $stmt_name);

foreach($ids as $id) {
    $data = $db->bquery($stmt_name, $id);
    var_dump($data);
}
```

`bquery()` returns an array with all the data.
If you want a query result resource instead then use `brquery()`:

```php
$stmt_name = 'insert1';
$db->prepare('insert into tcolor values(default, $1)', $stmt_name);
$colors = ['salmon', 'cyan', 'beige', 'indigo'];

foreach($colors as $c) {
    $db->bexec($stmt_name, $c);
}

$floors = [4, 3];
$stmt_name = 'select1';
$db->prepare('select * from tcolor where color_id >= $1', $stmt_name);

foreach($floors as $f) {
    $result = $db->brquery($stmt_name, $f);

    while($row = pg_fetch_object($result)) {
        print_r($row);
    }
}
```

## SQLite

Use the `Sqlite()` constructor to connect to an SQLite database:

```php
<?php

require 'vendor/autoload.php';

$db = new pjsql\Sqlite('mydb.db');

echo get_class($db->conn());
```

`conn()` above is an [SQLite3](https://www.php.net/manual/en/class.sqlite3.php) object.

Execute SQLite queries with `exec()` and `query()`:

```php
$db->exec('drop table if exists tshape');

$db->exec('create table tshape(name text)');

$db->exec('insert into tshape values("circle")');

$data = $db->query('select * from tshape');

print_r($data);
```

`exec()` and `query()` can take query parameters:

```php
$db->exec(
    'insert into tshape values(?), (?)',
    'triangle',
    'square');

$data = $db->query(
    'select * from tshape where rowid > ?',
    1);

print_r($data);
```

`query()` returns an array with all the data.
If you want an [SQLite3Result](https://www.php.net/manual/en/class.sqlite3result.php)
object instead then use `rquery()`:

```php
$db->exec(
    'insert into tshape values(?), (?)',
    'triangle',
    'square');

$result = $db->rquery(
    'select * from tshape where rowid > ?',
    0);

while($row = $result->fetchArray(SQLITE3_ASSOC)) {
    print_r($row);
}
```

Use `prepare()`, `bexec()` and `bquery()` to run a query more than once:

```php
$stmt = $db->prepare('insert into tshape values(?)');
$db->bexec($stmt, 'octagon');
$db->bexec($stmt, 'oval');
$db->bexec($stmt, 'circle');

$ids = [1, 2, 3, 4];
$stmt = $db->prepare('select name from tshape where rowid = ?');

foreach($ids as $id) {
    $data = $db->bquery($stmt, $id);
    print_r($data);
}
```

`bquery()` returns an array with all the data.
If you want an [SQLite3Result](https://www.php.net/manual/en/class.sqlite3result.php)
object instead then use `brquery()`:

```php
$stmt = $db->prepare('insert into tshape values(?)');
$shapes = ['star', 'heart', 'cross', 'diamond'];

foreach($shapes as $s) {
    $db->bexec($stmt, $s);
}

$stmt = $db->prepare('select * from tshape where rowid >= ?');
$floors = [4, 3, 4];

foreach($floors as $f) {
    $result = $db->brquery($stmt, $f);

    while($row = $result->fetchArray()) {
        print_r($row);
    }
}
```

With `exec()`, `query()`, `rquery()`, `bexec()`, `bquery()` and `brquery()`
you can put the parameter values into an array and use a types
string as the third argument in order to specify the query
parameter types:

```php
$db->exec(
    'insert into tshape values(?), (?)',
    ['square', 'circle'],
    'tt');

$data = $db->query(
    'select * from tshape where rowid < ?',
    [500],
    'i');

print_r($data);
```

The characters in the above types string use the following mapping:

```
i = SQLITE3_INTEGER
f = SQLITE3_FLOAT
t = SQLITE3_TEXT
b = SQLITE3_BLOB
n = SQLITE3_NULL
```

## Models

This is a MySQL model example.
PostgreSQL and SQLite models will look almost identical to this;
we will indicate the minor differences along the way.

Set up a model factory:

```php
<?php

namespace purple;

class ModelFactory extends \pjsql\AdapterFactory {
    protected static function databaseHandle() {
        return new \pjsql\Mysql(
            'host',
            'username',
            'password',
            'database');
    }
}
```

If you want to use PostgreSQL or SQLite instead of MySQL,
then instantiate a `\pjsql\Pgsql` or a `\pjsql\Sqlite`
instead of a `\pjsql\Mysql`.

Create a model:

```php
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
```

Get a model and call its methods:

```php
<?php

require 'vendor/autoload.php';

$dog_model = purple\ModelFactory::get('purple\DogModel');

$dog_model->install();

$dog_model->createDog('spike');
$dog_model->createDog('buster');
$dog_model->createDog('molly');

$data = $dog_model->getDogs();

print_r($data);
```

...
