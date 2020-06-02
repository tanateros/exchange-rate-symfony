<?php

namespace App\ValueObject;

final class Currency implements \JsonSerializable
{
    /**
     * @var string $code
     */
    private $code;

    /**
     * @param string $code
     */
    public function __construct(string $code)
    {
        if (!is_string($code)) {
            throw new \InvalidArgumentException('Currency code should be string');
        }

        if ($code === '') {
            throw new \InvalidArgumentException('Currency code should not be empty string');
        }

        if (strlen($code) !== 3) {
            throw new \LengthException('Currency code should not be empty string');
        }

        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param Currency $other
     * @return bool
     */
    public function equals(Currency $other): bool
    {
        return $this->getCode() === $other->getCode();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getCode();
    }

    /**
     * @return string
     */
    public function jsonSerialize(): string
    {
        return $this->code;
    }
}
