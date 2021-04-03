<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Dbal\Pdo\Factory;

use PDO;
use Throwable;
use GrizzIt\Dbal\Common\ConnectionInterface;
use GrizzIt\Dbal\Common\ConnectionFactoryInterface;
use GrizzIt\Dbal\Pdo\Exception\ConnectionException;
use GrizzIt\Dbal\Pdo\Component\Connection\PdoConnection;

class PdoConnectionFactory implements ConnectionFactoryInterface
{
    /**
     * Creates an instance of a connection
     *
     * @param string      $dsn
     * @param string      $username
     * @param string|null $password
     * @param array|null  $options
     * @param array|null  $attributes
     *
     * @return ConnectionInterface When the connection fails.
     */
    public function create(
        string $dsn,
        string $username,
        string $password = null,
        array $options = null,
        array $attributes = null
    ): ConnectionInterface {
        $attributes = $attributes ?? [PDO::ATTR_EMULATE_PREPARES => false];
        try {
            $connection = new PDO($dsn, $username, $password, $options);
            foreach ($attributes as $attribute => $value) {
                $connection->setAttribute($attribute, $value);
            }

            return new PdoConnection($connection);
        } catch (Throwable $exception) {
            throw new ConnectionException($exception);
        }
    }
}
