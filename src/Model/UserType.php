<?php

namespace App\Model;

use MabeEnum\Enum;

/**
 * Class UserType
 *
 * @method static UserType NATURAL()
 * @method static UserType LEGAL()
 *
 * @codeCoverageIgnore
 */
class UserType extends Enum
{
    public const NATURAL = 'natural';
    public const LEGAL = 'legal';
}
