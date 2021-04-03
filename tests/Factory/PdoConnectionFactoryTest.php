<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Dbal\Pdo\Tests\Factory;

use PHPUnit\Framework\TestCase;
use GrizzIt\Dbal\Pdo\Factory\PdoConnectionFactory;
use GrizzIt\Dbal\Pdo\Exception\ConnectionException;
use GrizzIt\Dbal\Pdo\Component\Connection\PdoConnection;

/**
 * @coversDefaultClass \GrizzIt\Dbal\Pdo\Factory\PdoConnectionFactory
 * @covers \GrizzIt\Dbal\Pdo\Exception\ConnectionException
 */
class PdoConnectionFactoryTest extends TestCase
{
    /**
     * @covers ::create
     *
     * @return void
     */
    public function testCreate(): void
    {
        $factory = new PdoConnectionFactory();
        $this->assertInstanceOf(
            PdoConnection::class,
            $factory->create(
                'mysql:dbname=test;host=localhost',
                'test',
                'test'
            )
        );

        $this->expectException(ConnectionException::class);

        $factory->create('foo', 'bar', 'baz');
    }
}
