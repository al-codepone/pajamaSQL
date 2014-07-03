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

## Overview

pajamaSQL is two things:

* a database wrapper that simplifies connecting to and calling SQL
* a model layer for isolating, grouping and reusing SQL

The database wrapper has five key features:

* connect to an SQL database via object instantiation
* execute a result-less SQL query with the `exec()` method
* get a 2d array of results using the `query()` method
* escape strings in SQL queries using the `esc()` method
* SQL error handling via PHP exceptions

The model layer can be summarized as follows:

* all models use the same database wrapper(i.e. the same database connection)
* get models via a static method call
* model methods call `exec()`, `query()` and `esc()` on the injected database wrapper

## LICENSE

MIT <http://ryf.mit-license.org/>
