<?php

namespace App\Model;

/**
 * Class OperationContext
 *
 * @codeCoverageIgnore
 */
class OperationContext implements OperationContextInterface
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var OperationType
     */
    private $operationType;

    /**
     * OperationContext constructor.
     *
     * @param User          $user
     * @param \DateTime     $date
     * @param OperationType $operationType
     */
    public function __construct(User $user, \DateTime $date, OperationType $operationType)
    {
        $this->user = $user;
        $this->date = $date;
        $this->operationType = $operationType;
    }

    /**
     * @inheritdoc
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return OperationContext
     */
    public function setUser(User $user): OperationContext
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     *
     * @return OperationContext
     */
    public function setDate(\DateTime $date): OperationContext
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOperationType(): OperationType
    {
        return $this->operationType;
    }

    /**
     * @param OperationType $operationType
     *
     * @return OperationContext
     */
    public function setOperationType(OperationType $operationType): OperationContext
    {
        $this->operationType = $operationType;

        return $this;
    }
}
