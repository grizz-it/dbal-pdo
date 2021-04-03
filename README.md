[![Build Status](https://travis-ci.com/grizz-it/dbal-pdo.svg?branch=master)](https://travis-ci.com/grizz-it/dbal-pdo)

# GrizzIT DBAL PDO

GrizzIT DBAL PDO provides a PDO implementation for
[GrizzIT DBAL](https://github.com/grizz-it/dbal). This package only implements
the database connection and transaction part.

## Installation

To install the package run the following command:

```
composer require grizz-it/dbal-pdo
```

## Usage

### Creating a connection

To establish a connection with the database through the
`GrizzIt\Dbal\Pdo\Component\Connection\PdoConnection` object, it is preferred to use the
supplied factory in the package.

First of, initialize the factory by adding:
```php
<?php

use GrizzIt\Dbal\Pdo\Factory\PdoConnectionFactory;

$factory = new PdoConnectionFactory;
```

Then proceed to create an instance of `PdoConnection` by calling the `create` method.

```php
<?php

$connection = $factory->create(
    'mysql:dbname=test;host=localhost',
    'test',
    'test'
);
```

The factory will then create a `PDO` object, inject it into the `PdoConnection`
object and return the connection object.

The create method has the following parameters:

#### string $dsn

This parameter expects a DSN string.
The Data Source Name, or DSN, contains the information required to connect to
the database. An example of how to compose such a string for MySQL can be found
[here](https://www.php.net/manual/en/ref.pdo-mysql.connection.php).

#### string $username

This string expects the username of the database user through which you wish to
connect your application to the database.

#### string $password (optional)

The password field expects the password for the previously mentioned user.

#### array $options (optional)

This parameter expects driver specific connection options in key value pairs.
These can be found through the [PDO drivers](https://www.php.net/manual/en/pdo.drivers.php)
page on PHP.net.

#### array $attributes (optional)

This parameter expects PDO attributes to be set in key value pairs.
These options can be found [here](https://www.php.net/manual/en/pdo.setattribute.php).
All these options are immediately set on the PDO object after initialisation.

### Calling the database

After the connection has been established with the database it is possible to
send query objects to the `PdoConnection`, which will run their queries on the
database.

After a query object is fully assembled it can be executed in the following ways:
```php
// Immediate call to the database.
$result = $connection->query($queryObject);

// Transactional call to the database.
$connection->startTransaction();
$result = $connection->query($queryObject);
$connection->commit();
// It is also possible to rollback a transaction before it is committed:
$connection->rollback();
```

If the query was an insertion of a new record, the insert ID can be retrieved
by calling lastTransactionId:
```php
$connection->lastTransactionId();
```

### Reading the result

The results of a query are send back to the application through an instance of
the `GrizzIt\Dbal\Pdo\Component\Result\PdoQueryResult` class. It is possible to
iterate over this object by running it in a foreach loop. If all records should
be retrieved at once, then the `fetchAll()` method can be called on the result
object.

The amount of affected records can be retrieved by "counting" the object:
```php
count($result); //Returns affected rows.
```

To assert the success of the query, the `isSuccess` method can be called on the
result object.

The status code and errors (as a result of a failing query) can be retrieved by
calling `getErrors` and `getStatusCode` on the result object.

All SQLState status codes can be found [here](https://docs.oracle.com/cd/A84870_01/doc/appdev.816/a58231/appd.htm).

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## MIT License

Copyright (c) GrizzIT

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
