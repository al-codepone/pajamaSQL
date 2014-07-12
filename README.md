# pajamaSQL

pajamaSQL is a PHP SQL database layer.
It supports MySQL, PostgreSQL and SQLite.
The first release is still in development.
This README is incomplete.

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

## conn() and error()

Use `conn()` to access the full vendor specific database extension:

```php
if($status = $mysql->conn()->stat()) {
    echo $status;
}
else {
    //call error() when using conn()
    $mysql->error();
}
```

## Models

Setup a model factory:

```php
namespace myns;

class ModelFactory extends \pjsql\AdapterFactory {
    protected static function databaseHandle() {

        //all models share a single instance of this object
        return new \pjsql\Mysql('host', 'username', 'password', 'db');
    }
}
```

Create model(s):

```php
namespace myns;

class WordModel extends \pjsql\DatabaseAdapter {
    public function install() {
        $this->exec('CREATE TABLE tword (
            word_id INT AUTO_INCREMENT PRIMARY KEY,
            word VARCHAR(32))');
    }

    public function create($word) {
        $this->exec(sprintf('INSERT INTO tword (word) VALUES("%s")',
            $this->esc($word)));
    }

    public function get() {
        return $this->query('SELECT * FROM tword');
    }
}
```

Get model(s) and call their methods:

```php
//must pass full namespace to get()
$wordModel = myns\ModelFactory::get('myns\WordModel');

$wordModel->install();
$wordModel->create('pink');
$wordModel->create('bread');

var_dump($wordModel->get());
```

## Errors

Handle SQL errors with `set_exception_handler()`:

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

## LICENSE

MIT <http://ryf.mit-license.org/>
