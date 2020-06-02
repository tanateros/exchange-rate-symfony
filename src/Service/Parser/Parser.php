<?php

namespace App\Service\Parser;

use App\DBAL\EnumRateSourceType;
use App\Entity\Rate;
use App\Repository\RateRepository;
use App\ValueObject\Currency;
use App\ValueObject\Money;
use Clue\React\Buzz\Browser;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;

class Parser
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Browser
     */
    private $client;

    public function __construct(EntityManager $entityManager, Browser $client)
    {
        $this->em = $entityManager;
        $this->client = $client;
    }

    /**
     * @param RateRepository $rateRepository
     */
    public function eachParse(RateRepository $rateRepository): void
    {
        $sources = EnumRateSourceType::RATE_SOURCE;
        $ratesBySource = $rateRepository->findBy([
            'source' => EnumRateSourceType::VALUES,
        ]);
        $exchangeRates = [];

        foreach ($ratesBySource as $rateBySource) {
            $exchangeRates[$rateBySource->getSource()]
                [$rateBySource->getCurrencyFrom()]
                    [$rateBySource->getCurrencyTo()] = $rateBySource;
        }

        foreach ($sources as $key => $source) {
            $this->client->get($source['url'])->then(
                function (ResponseInterface $response) use ($exchangeRates, $key, $source) {
                    $rateParser = new RatesContextParser(new $source['class']());
                    $currencyRates = $rateParser->doParse($response->getBody());
                    $toCurrency = new Currency($source['crossCurrency']);

                    /** @var Money $rate */
                    foreach ($currencyRates as $currencyRate) {
                        if (empty($exchangeRates[$key][$source['crossCurrency']][$currencyRate->getCurrency()])) {
                            $rate = new Rate();
                            $rate->setCurrencyFrom($currencyRate->getCurrency())
                                ->setCurrencyTo($toCurrency)
                                ->setRate($currencyRate->getAmount())
                                ->setSource($key)
                            ;
                       } else {
                            $rate = $exchangeRates[$key][$source['crossCurrency']][$currencyRate->getCurrency()]
                                ->setRate($currencyRate->getAmount());
                        }

                        $this->em->persist($rate);
                    }

                    $this->em->flush();
                }
            );
        }
    }
}
