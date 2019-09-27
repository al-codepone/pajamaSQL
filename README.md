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

...
