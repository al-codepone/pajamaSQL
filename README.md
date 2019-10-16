# pajamaSQL

pajamaSQL is a PHP SQL database layer.
It supports MySQL, PostgreSQL and SQLite.

## Table of Contents

[Documentation](#documentation)\
[Requirements](#requirements)\
[Source Code](#source-code)\
[Unit Tests](#unit-tests)\
[Examples](#examples)\
[Installation](#installation)\
[Overview](#overview)\
[MySQL](#mysql)\
[PostgreSQL](#postgresql)\
[SQLite](#sqlite)\
[Models](#models)\
[Error Handling](#error-handling)\
[License](#license)

## Documentation

This [README](https://github.com/al-codepone/pajamaSQL/blob/master/README.md)
is currently the only documentation.

## Requirements

**PHP 5.6.0+ or 7+**; [Composer](https://getcomposer.org/);
and at least one of the follow PHP vendor specific database extensions:
[Mysqli](http://www.php.net/manual/en/book.mysqli.php),
[PostgreSQL](http://www.php.net/manual/en/book.pgsql.php) and/or
[SQLite3](http://www.php.net/manual/en/book.sqlite3.php). The unit tests
need **PHP 7.2.0+**.


## Source Code

This [project](https://github.com/al-codepone/pajamaSQL)
and its [source code](https://github.com/al-codepone/pajamaSQL/tree/master/src/pjsql)
are available on GitHub.

## Unit Tests

There are unit tests in the
[tests](https://github.com/al-codepone/pajamaSQL/tree/master/tests) directory.
You will need to edit the database credentials at the top of
`MysqlTest.php` and `PgsqlTest.php`.

## Examples

All the example code in this README is in the
[examples](https://github.com/al-codepone/pajamaSQL/tree/master/examples)
directory. The examples directory also contains additional examples that are not
in this README.
You need to run `composer install` in the examples directory before running
any of the examples.

## Installation

Install using composer:

```javascript
{
    "require": {
        "pajamasql/pajamasql": "1.0.0",
    }
}
```

## Overview

pajamaSQL wraps [Mysqli](http://www.php.net/manual/en/book.mysqli.php),
[PostgreSQL](http://www.php.net/manual/en/book.pgsql.php) and
[SQLite3](http://www.php.net/manual/en/book.sqlite3.php)
prepared statements. These are the available methods: `exec()`, `query()`,
`rquery()`, `prepare()`, `bexec()`, `bquery()`, `brquery()`, `conn()`,
`error()` and `esc()`. Basic usage looks like this:

```php
$db->exec(
    'insert into foo values(?), (?)',
    'bar',
    'baz');

$data = $db->query(
    'select * from foo where rowid > ?',
    1);
```

There is also a model layer. A basic model looks like this:

```php
<?php

namespace purple;

class FooModel extends \pjsql\DatabaseAdapter {
    public function createFoo($name) {
        $this->exec(
            'insert into foo values(?)',
            $name);
    }    
}
```

Model instantiation and usage:

```php
<?php

require 'vendor/autoload.php';

$foo_model = purple\ModelFactory::get('purple\FooModel');

$foo_model->createFoo('fred');
```

## MySQL

Use `Mysql()` to connect to a database:

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

`conn()` above is a [mysqli](https://www.php.net/manual/en/class.mysqli.php) object.

Execute queries with `exec()` and `query()`:

```php
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

`query()` returns an array with all the data.
If you want a
[mysqli_result](https://www.php.net/manual/en/class.mysqli-result.php)
object instead then use `rquery()`:

```php
$result = $db->rquery('select * from tanimal');

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

`bquery()` returns an array with all the data.
If you want a
[mysqli_result](https://www.php.net/manual/en/class.mysqli-result.php)
object instead then use `brquery()`:

```php
$floors = [1, 5];
$stmt = $db->prepare('select name from tanimal where animal_id >= ?');

foreach($floors as $f) {
    $result = $db->brquery($stmt, $f);

    while($row = $result->fetch_assoc()) {
        print_r($row);
    }
}
```

With all the exec and query methods
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

Use `Pgsql()` to connect to a database:

```php
<?php

require 'vendor/autoload.php';

$db = new pjsql\Pgsql('dbname=foo user=bar password=baz');

echo pg_host($db->conn());
```

`conn()` above is a PostgreSQL connection resource that is returned
by [pg_connect()](https://www.php.net/manual/en/function.pg-connect.php).

Execute queries with `exec()` and `query()`:

```php
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
$result = $db->rquery('select * from tcolor');

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

Use `Sqlite()` to connect to a database:

```php
<?php

require 'vendor/autoload.php';

$db = new pjsql\Sqlite('mydb.db');

echo get_class($db->conn());
```

`conn()` above is an [SQLite3](https://www.php.net/manual/en/class.sqlite3.php) object.

Execute queries with `exec()` and `query()`:

```php
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
$result = $db->rquery('select * from tshape');

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
$stmt = $db->prepare('select * from tshape where rowid >= ?');
$floors = [4, 3, 4];

foreach($floors as $f) {
    $result = $db->brquery($stmt, $f);

    while($row = $result->fetchArray()) {
        print_r($row);
    }
}
```

With all the exec and query methods
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

If you want to use PostgreSQL or SQLite,
then use `Pgsql()` or `Sqlite()` instead of the above `Mysql()`.

Create a model:

```php
<?php

namespace purple;

class DogModel extends \pjsql\DatabaseAdapter {
    public function install() {
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
        return $this->query('select * from tdog');
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

$data = $dog_model->getDogs();

print_r($data);
```

## Error Handling

With the exec, query and prepare methods
you can handle errors with an exception handler:

```php
set_exception_handler(function($e) {
    if($e instanceof pjsql\DatabaseException) {
        die($e->getMessage());
    }
    else {
        throw $e;
    }
});
```

If you use `conn()`, then call `error()` when
appropriate(MySQL example):

```php
if($status = $db->conn()->stat()) {
    echo $status;
}
else {
    $db->error();
}
```

`error()` above will thrown an exception that will be
caught by the above exception handler.

## LICENSE

MIT <http://ryf.mit-license.org/>
