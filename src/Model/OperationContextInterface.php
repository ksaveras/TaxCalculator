<?php

namespace App\Model;

/**
 * Class OperationContextInterface
 *
 * @codeCoverageIgnore
 */
interface OperationContextInterface
{
    /**
     * @return User
     */
    public function getUser(): User;

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime;

    /**
     * @return OperationType
     */
    public function getOperationType(): OperationType;
}
