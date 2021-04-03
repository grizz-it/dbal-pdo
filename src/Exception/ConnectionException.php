<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Dbal\Pdo\Exception;

use Exception;
use Throwable;

class ConnectionException extends Exception
{
    /**
     * Constructor.
     *
     * @param Throwable $exception
     */
    public function __construct(Throwable $exception)
    {
        parent::__construct(
            'Could not connect to database.',
            (int) $exception->getCode()
        );
    }
}
