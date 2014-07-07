# pajamaSQL

pajamaSQL is a PHP SQL database layer.
It supports MySQL, PostgreSQL and SQLite.
The first release is still in development.
This README is incomplete.

## Documentation

This [README](https://github.com/al-codepone/pajamaSQL/blob/master/README.md)
is currently the only documentation.

## Requirements

\>= **PHP 5.3**

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
        "pajamasql/pajamasql": "dev-master",
    }
}
```

## Connect

Connect to MySQL:

```php
$mysql = new pjsql\Mysql('host', 'username', 'password', 'db');
```

Connect to PostgreSQL:

```php
$pgsql = new pjsql\Pgsql('dbname=mydb user=jon password=123456');
```

Connect to SQLite:

```php
$sqlite = new pjsql\Sqlite('mydb.db');
```

## exec()

Use `exec()` to execute a result-less SQL query:

```php
$sqlite->exec('CREATE TABLE tword (word TEXT)');
```

## query()

Use `query()` to get a 2d array of results:

```php
var_dump($pgsql->query('SELECT * FROM tword'));
```

## esc()

Use `esc()` to escape strings in SQL queries:

```php
$mysql->exec(sprintf('INSERT INTO tword (word) VALUES("%s")',
    $mysql->esc('"hello"')));
```

## LICENSE

MIT <http://ryf.mit-license.org/>
