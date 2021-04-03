<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Dbal\Pdo\Tests\Component\Result;

use PHPUnit\Framework\TestCase;
use GrizzIt\Dbal\Pdo\Component\Result\PdoQueryResult;
use GrizzIt\Dbal\Pdo\Component\Result\PdoIterableQueryResult;
use PDOStatement;

/**
 * @coversDefaultClass \GrizzIt\Dbal\Pdo\Component\Result\PdoQueryResult
 */
class PdoQueryResultTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::isSuccess
     * @covers ::getErrors
     * @covers ::getStatusCode
     * @covers ::count
     * @covers ::getIterator
     * @covers ::fetchAll
     *
     * @return void
     */
    public function testResultContent(): void
    {
        $statementContent = [
            [
                'id' => '1',
                'foo' => 'bar'
            ]
        ];

        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->expects(static::exactly(2))
            ->method('errorCode')
            ->willReturn('00000');

        $statementMock->expects(static::once())
            ->method('fetchAll')
            ->willReturn($statementContent);

        $statementMock->expects(static::once())
            ->method('errorInfo')
            ->willReturn(
                [
                    '00000',
                    '0',
                    ''
                ]
            );

        $statementMock->expects(static::exactly(2))
            ->method('rowCount')
            ->willReturn(1);

        $subject = new PdoQueryResult($statementMock);

        $this->assertInstanceOf(
            PdoQueryResult::class,
            $subject
        );

        $this->assertEquals(true, $subject->isSuccess());
        $this->isType('array', $subject->getErrors());
        $this->assertEquals('00000', $subject->getStatusCode());
        $this->assertEquals(1, count($subject));
        $this->assertInstanceOf(
            PdoIterableQueryResult::class,
            $subject->getIterator()
        );

        $this->assertEquals(
            $statementContent,
            $subject->fetchAll()
        );
    }
}
