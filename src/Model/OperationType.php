<?php

namespace App\Model;

use MabeEnum\Enum;

/**
 * Class OperationType.
 *
 * @method static OperationType CASH_OUT()
 * @method static OperationType CASH_IN()
 *
 * @codeCoverageIgnore
 */
class OperationType extends Enum
{
    public const CASH_OUT = 'cash_out';
    public const CASH_IN = 'cash_in';
}
