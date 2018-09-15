<?php

namespace App\Model;

/**
 * Class User.
 *
 * @codeCoverageIgnore
 */
class User
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var UserType
     */
    private $type;

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return User
     */
    public function setId(string $id): User
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return UserType
     */
    public function getType(): ?UserType
    {
        return $this->type;
    }

    /**
     * @param UserType $type
     *
     * @return User
     */
    public function setType(UserType $type): User
    {
        $this->type = $type;

        return $this;
    }
}
