<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Dbal\Pdo\Exception;

use Exception;

class QueryException extends Exception
{
    /**
     * Constructor.
     *
     * @param string $query
     * @param string $errorCode
     * @param array $errorContent
     */
    public function __construct(
        string $query,
        string $errorCode,
        array $errorContent
    ) {
        parent::__construct(
            sprintf(
                'There was an error during the execution of %s with code: %s, %s',
                $query,
                $errorCode,
                implode(', ', $errorContent)
            )
        );
    }
}
