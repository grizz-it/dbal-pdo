<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\Dbal\Pdo\Tests\Fixtures;

use GrizzIt\Dbal\Common\QueryInterface;
use GrizzIt\Dbal\Common\ParameterizedQueryComponentInterface;

interface ParameterizedQueryInterface extends QueryInterface, ParameterizedQueryComponentInterface
{

}
