<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Dbal\Pdo\Tests\Component\Connection;

use PDO;
use PDOStatement;
use RuntimeException;
use PHPUnit\Framework\TestCase;
use GrizzIt\Dbal\Common\QueryInterface;
use GrizzIt\Dbal\Common\QueryResultInterface;
use GrizzIt\Dbal\Pdo\Exception\QueryException;
use GrizzIt\Dbal\Pdo\Component\Connection\PdoConnection;
use GrizzIt\Dbal\Pdo\Tests\Fixtures\ParameterizedQueryInterface;

/**
 * @coversDefaultClass \GrizzIt\Dbal\Pdo\Component\Connection\PdoConnection
 * @covers \GrizzIt\Dbal\Pdo\Exception\QueryException
 */
class PdoConnectionTest extends TestCase
{
    /**
     * @covers ::__construct
     *
     * @return void
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(
            PdoConnection::class,
            new PdoConnection($this->createMock(PDO::class))
        );
    }

    /**
     * @covers ::startTransaction
     *
     * @return void
     */
    public function testStartTransaction(): void
    {
        $pdoMock = $this->createMock(PDO::class);

        $pdoMock->expects(static::exactly(2))
            ->method('inTransaction')
            ->willReturn(false);

        $pdoMock->expects(static::exactly(2))
            ->method('beginTransaction')
            ->willReturnOnConsecutiveCalls(true, false);

        $subject = new PdoConnection($pdoMock);
        $subject->startTransaction();
        $this->expectException(RuntimeException::class);
        $subject->startTransaction();
    }

    /**
     * @covers ::commit
     *
     * @return void
     */
    public function testCommit(): void
    {
        $pdoMock = $this->createMock(PDO::class);

        $pdoMock->expects(static::exactly(2))
            ->method('inTransaction')
            ->willReturn(true);

        $pdoMock->expects(static::exactly(2))
            ->method('commit')
            ->willReturnOnConsecutiveCalls(true, false);

        $subject = new PdoConnection($pdoMock);
        $subject->commit();
        $this->expectException(RuntimeException::class);
        $subject->commit();
    }

    /**
     * @covers ::rollback
     *
     * @return void
     */
    public function testRollback(): void
    {
        $pdoMock = $this->createMock(PDO::class);

        $pdoMock->expects(static::exactly(2))
            ->method('inTransaction')
            ->willReturn(true);

        $pdoMock->expects(static::exactly(2))
            ->method('rollback')
            ->willReturnOnConsecutiveCalls(true, false);

        $subject = new PdoConnection($pdoMock);
        $subject->rollback();
        $this->expectException(RuntimeException::class);
        $subject->rollback();
    }

    /**
     * @covers ::lastInsertId
     *
     * @return void
     */
    public function testLastInsertId(): void
    {
        $pdoMock = $this->createMock(PDO::class);

        $pdoMock->expects(static::once())
            ->method('lastInsertId')
            ->willReturn('1');

        $subject = new PdoConnection($pdoMock);
        $this->assertEquals('1', $subject->lastInsertId());
    }

    /**
     * @covers ::query
     *
     * @return void
     */
    public function testQuery(): void
    {
        $pdoMock = $this->createMock(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);
        $parameterQueryMock = $this->createMock(
            ParameterizedQueryInterface::class
        );

        $parameterQueryMock->expects(static::once())
            ->method('getQuery')
            ->willReturn('foo');

        $parameterQueryMock->expects(static::once())
            ->method('getParameters')
            ->willReturn([]);

        $statementMock->expects(static::once())
            ->method('execute')
            ->with([]);

        $pdoMock->expects(static::once())
            ->method('prepare')
            ->with('foo')
            ->willReturn($statementMock);

        $queryMock = $this->createMock(
            QueryInterface::class
        );

        $queryMock->expects(static::once())
            ->method('getQuery')
            ->willReturn('foo');

        $pdoMock->expects(static::once())
            ->method('query')
            ->with('foo')
            ->willReturn($statementMock);

        $subject = new PdoConnection($pdoMock);

        $this->assertInstanceOf(
            QueryResultInterface::class,
            $subject->query($parameterQueryMock)
        );

        $this->assertInstanceOf(
            QueryResultInterface::class,
            $subject->query($queryMock)
        );
    }

    /**
     * @covers ::query
     *
     * @return void
     */
    public function testFailedQuery(): void
    {
        $pdoMock = $this->createMock(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);

        $queryMock = $this->createMock(
            QueryInterface::class
        );

        $queryMock->expects(static::exactly(2))
            ->method('getQuery')
            ->willReturn('foo');

        $pdoMock->expects(static::once())
            ->method('query')
            ->with('foo')
            ->willReturn(false);

        $subject = new PdoConnection($pdoMock);

        $this->expectException(QueryException::class);

        $subject->query($queryMock);
    }
}
