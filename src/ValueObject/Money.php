<?php

namespace App\ValueObject;

class Money implements \JsonSerializable
{
    /**
     * @var integer
     */
    private $amount;

    /**
     * @var Currency
     */
    private $currency;

    public function __construct($amount, $currency)
    {
        $this->amount = $amount;
        $this->currency = $this->handleCurrencyArgument($currency);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'amount'   => $this->amount,
            'currency' => $this->currency->getCode(),
        ];
    }

    /**
     * @return Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param Money $other
     * @return int
     * @throws \Exception
     */
    public function compareTo(Money $other): int
    {
        $this->assertSameCurrency($this, $other);

        if ($this->amount === $other->getAmount()) {
            return 0;
        }

        return $this->amount < $other->getAmount() ? -1 : 1;
    }

    /**
     * @param Money $other
     * @return bool
     * @throws \Exception
     */
    public function equals(Money $other): bool
    {
        return $this->compareTo($other) === 0;
    }

    /**
     * @param $currency
     * @return Currency|string
     */
    private function handleCurrencyArgument($currency)
    {
        if (!$currency instanceof Currency && !is_string($currency)) {
            throw new \InvalidArgumentException('$currency must be an object of type Currency or a string');
        }

        if (is_string($currency)) {
            $currency = new Currency($currency);
        }

        return $currency;
    }

    /**
     * @param Money $a
     * @param Money $b
     * @throws \Exception
     */
    private function assertSameCurrency(Money $a, Money $b): void
    {
        if ($a->getCurrency() !== $b->getCurrency()) {
            throw new \Exception('Currency mismatch exception');
        }
    }
}
