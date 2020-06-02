<?php

namespace App\Service;

use App\DBAL\EnumRateSourceType;
use App\Entity\Rate;
use App\Repository\RateRepository;
use App\ValueObject\Currency;

class RateService
{
    public const DEFAULT_AMOUNT = 1.0;

    /**
     * @var RateRepository
     */
    protected $rateRepository;

    /**
     * @var string
     */
    protected $source;

    /**
     * RateService constructor.
     * @param RateRepository $rateRepository
     * @param string $source
     */
    public function __construct(RateRepository $rateRepository, string $source)
    {
        $this->rateRepository = $rateRepository;
        $this->source = $source;
    }

    /**
     * @param Currency $from
     * @param Currency $to
     * @param float|null $fromAmount
     * @return float
     */
    public function getRate(Currency $from, Currency $to, ?float $fromAmount): float
    {
        if (null === $fromAmount) {
            $fromAmount = self::DEFAULT_AMOUNT;
        }

        /** @var Rate|null $rate */
        $rate = $this->rateRepository->findOneBy([
            'source' => $this->source,
            'currency_from' => $from,
            'currency_to' => $to,
        ]);

        if ($rate !== null) {
            return $rate->getRate() * $fromAmount;
        }

        $crossCurrency = EnumRateSourceType::RATE_SOURCE[$this->source]['crossCurrency'];
        $rates = $this->rateRepository->findBy([
            'source' => $this->source,
            'currency_from' => [$from, $to],
            'currency_to' => $crossCurrency,
        ]);

        if (count($rates) === 2) {
            $rate = $from->equals(new Currency($rates[0]->getCurrencyFrom()))
                ? $rates[0]->getRate() / $rates[1]->getRate()
                : $rates[1]->getRate() / $rates[0]->getRate();
            return $rate * $fromAmount;
        }

        return Rate::DEFAULT_RATE_VALUE;
    }
}
