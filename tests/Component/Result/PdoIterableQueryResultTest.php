<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Dbal\Pdo\Tests\Component\Result;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use GrizzIt\Dbal\Pdo\Component\Result\PdoIterableQueryResult;

/**
 * @coversDefaultClass \GrizzIt\Dbal\Pdo\Component\Result\PdoIterableQueryResult
 */
class PdoIterableQueryResultTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::count
     * @covers ::fetchAll
     * @covers ::current
     * @covers ::key
     * @covers ::next
     * @covers ::rewind
     * @covers ::valid
     * @covers ::setFetchMode
     * @covers ::getFetchMode
     *
     * @return void
     */
    public function testIterable(): void
    {
        $entryData = [
            'id' => '1',
            'foo' => 'bar'
        ];

        $statementMock = $this->createMock(PDOStatement::class);

        $statementMock->expects(static::once())
            ->method('rowCount')
            ->willReturn(3);

        $statementMock->expects(static::exactly(2))
            ->method('fetch')
            ->willReturn($entryData);

        $statementMock->expects(static::once())
            ->method('fetchAll')
            ->willReturn([$entryData]);

        $subject = new PdoIterableQueryResult($statementMock);

        $this->assertEquals(PDO::FETCH_ASSOC, $subject->getFetchMode());
        $subject->setFetchMode(PDO::FETCH_BOTH);
        $this->assertEquals(PDO::FETCH_BOTH, $subject->getFetchMode());

        $this->assertInstanceOf(
            PdoIterableQueryResult::class,
            $subject
        );

        $this->assertEquals(3, count($subject));

        foreach ($subject as $key => $entry) {
            $this->isType('int', $key);
            $this->assertEquals($entryData, $entry);

            if ($key === 1) {
                break;
            }
        }

        $this->assertEquals(
            [$entryData, $entryData, $entryData],
            $subject->fetchAll()
        );
    }
}
