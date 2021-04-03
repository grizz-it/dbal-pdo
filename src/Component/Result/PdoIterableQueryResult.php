<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Dbal\Pdo\Component\Result;

use PDO;
use Iterator;
use Countable;
use PDOStatement;

class PdoIterableQueryResult implements Iterator, Countable
{
    /**
     * Contains the PDOStatement object.
     *
     * @var PDOStatement
     */
    private PDOStatement $statement;

    /**
     * Stores the results of the query iteration.
     *
     * @var array
     */
    private array $resultStore = [];

    /**
     * Contains the count of the amount of entries.
     *
     * @var int|null
     */
    private ?int $entryCount;

    /**
     * Contains the current iterator position.
     *
     * @var int
     */
    private int $currentPosition = 0;

    /**
     * Contains the fetch mode for the result set.
     *
     * @var int
     */
    private int $fetchMode = PDO::FETCH_ASSOC;

    /**
     * Constructor
     *
     * @param  PDOStatement $statement
     */
    public function __construct(PDOStatement $statement)
    {
        $this->statement  = $statement;
        $this->entryCount = $statement->rowCount();
    }

    /**
     * Returns the amount of entries in the iterator.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->entryCount;
    }

    /**
     * Sets the fetch mode for queries.
     *
     * @param int $fetchMode
     *
     * @return void
     */
    public function setFetchMode(int $fetchMode): void
    {
        $this->fetchMode = $fetchMode;
    }

    /**
     * Gets the fetch mode for queries.
     *
     * @return int
     */
    public function getFetchMode(): int
    {
        return $this->fetchMode;
    }

    /**
     * Fetches all rows from the result set.
     *
     * @return array
     */
    public function fetchAll(): array
    {
        if (count($this->resultStore) < $this->entryCount) {
            $this->resultStore = array_merge(
                $this->resultStore,
                $this->statement->fetchAll($this->fetchMode)
            );
        }

        return $this->resultStore;
    }

    /**
     * Returns the value of the current iteration position.
     *
     * @return mixed
     */
    public function current()
    {
        if (!isset($this->resultStore[$this->currentPosition])) {
            $this->resultStore[] = $this->statement->fetch($this->fetchMode);
        }

        return $this->resultStore[$this->currentPosition];
    }

    /**
     * Retrieves the current key.
     *
     * @return int
     */
    public function key(): int
    {
        return $this->currentPosition;
    }

    /**
     * Sets the current position to the next position.
     *
     * @return void
     */
    public function next(): void
    {
        $this->currentPosition++;
    }

    /**
     * Resets the pointer.
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->currentPosition = 0;
    }

    /**
     * Returns whether the current position is valid.
     *
     * @return bool
     */
    public function valid(): bool
    {
        return $this->currentPosition < $this->entryCount;
    }
}
