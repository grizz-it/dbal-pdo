<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Dbal\Pdo\Component\Result;

use IteratorAggregate;
use PDOStatement;
use GrizzIt\Dbal\Common\QueryResultInterface;

class PdoQueryResult implements IteratorAggregate, QueryResultInterface
{
    /**
     * Contains the PDOStatement object.
     *
     * @var PDOStatement
     */
    private PDOStatement $statement;

    /**
     * Contains the iterator object.
     *
     * @var PdoIterableQueryResult
     */
    private PdoIterableQueryResult $iterator;

    /**
     * Constructor
     *
     * @param  PDOStatement $statement
     */
    public function __construct(PDOStatement $statement)
    {
        $this->statement = $statement;
        $this->iterator = new PdoIterableQueryResult($this->statement);
    }

    /**
     * Returns whether the query was a success or not.
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->getStatusCode() === '00000';
    }

    /**
     * Retrieves all errors related to the query.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->statement->errorInfo();
    }

    /**
     * Returns the status code of the query.
     *
     * @return string
     */
    public function getStatusCode(): string
    {
        return $this->statement->errorCode();
    }

    /**
     * Retrieves all rows from the result.
     *
     * @return array
     */
    public function fetchAll(): array
    {
        return $this->iterator->fetchAll();
    }

    /**
     * Returns the amount of rows affected by the executed query.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->statement->rowCount();
    }

    /**
     * Retrieves a traversable object which iterates over the query results.
     *
     * @return PdoIterableQueryResult
     */
    public function getIterator(): PdoIterableQueryResult
    {
        return $this->iterator;
    }
}
